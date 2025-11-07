<?php
declare(strict_types=1);

const CHAT_STORAGE_DIR = __DIR__ . '/storage';
const CHAT_MESSAGES_FILE = CHAT_STORAGE_DIR . '/messages.json';
const CHAT_MAX_MESSAGES = 200;
const CHAT_ACTIVE_WINDOW = 300; // seconds

header_remove('X-Powered-By');

/**
 * Ensure the chat storage directory and message file exist.
 */
function chat_ensure_storage(): void
{
    if (!is_dir(CHAT_STORAGE_DIR)) {
        mkdir(CHAT_STORAGE_DIR, 0775, true);
    }

    if (!file_exists(CHAT_MESSAGES_FILE)) {
        file_put_contents(CHAT_MESSAGES_FILE, json_encode([], JSON_UNESCAPED_SLASHES));
    }
}

/**
 * Load all messages from storage.
 *
 * @return array<int, array<string, mixed>>
 */
function chat_read_messages(): array
{
    chat_ensure_storage();

    $contents = file_get_contents(CHAT_MESSAGES_FILE);
    if ($contents === false || $contents === '') {
        return [];
    }

    $messages = json_decode($contents, true);
    if (!is_array($messages)) {
        return [];
    }

    return $messages;
}

/**
 * Append a message to storage and return the saved payload.
 *
 * @param string $author
 * @param string $body
 * @param string $color
 * @return array<string, mixed>
 */
function chat_append_message(string $author, string $body, string $color): array
{
    chat_ensure_storage();

    $fp = fopen(CHAT_MESSAGES_FILE, 'c+');
    if ($fp === false) {
        throw new RuntimeException('Unable to open chat storage.');
    }

    try {
        if (!flock($fp, LOCK_EX)) {
            throw new RuntimeException('Unable to lock chat storage.');
        }

        rewind($fp);
        $contents = stream_get_contents($fp);
        $messages = [];

        if ($contents !== false && $contents !== '') {
            $decoded = json_decode($contents, true);
            if (is_array($decoded)) {
                $messages = $decoded;
            }
        }

        $message = [
            'id' => uniqid('msg_', true),
            'author' => $author,
            'message' => $body,
            'color' => $color,
            'timestamp' => round(microtime(true), 6),
        ];

        $messages[] = $message;

        if (count($messages) > CHAT_MAX_MESSAGES) {
            $messages = array_slice($messages, -CHAT_MAX_MESSAGES);
        }

        ftruncate($fp, 0);
        rewind($fp);
        fwrite($fp, json_encode($messages, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRESERVE_ZERO_FRACTION));
        fflush($fp);

        return $message;
    } finally {
        if (isset($fp) && is_resource($fp)) {
            flock($fp, LOCK_UN);
            fclose($fp);
        }
    }
}

/**
 * Sanitize and normalize a provided display name.
 */
function chat_sanitize_name(?string $name): string
{
    $name = trim((string) $name);
    if ($name === '') {
        return 'Guest';
    }

    $name = preg_replace('/\s+/', ' ', $name);
    $name = strip_tags($name ?? '');
    $name = mb_substr($name, 0, 32);

    return $name === '' ? 'Guest' : $name;
}

/**
 * Sanitize message body content.
 */
function chat_sanitize_message(?string $body): string
{
    $body = trim((string) $body);
    $body = preg_replace("/\r\n|\r/", "\n", $body);
    $body = strip_tags($body);
    $body = preg_replace('/[\x00-\x09\x0B-\x1F\x7F]/u', '', $body);
    $body = mb_substr($body, 0, 500);

    return $body;
}

/**
 * Validate a provided colour string.
 */
function chat_validate_color(?string $color): string
{
    $color = trim((string) $color);
    if ($color === '') {
        return '#38bdf8';
    }

    if (preg_match('/^#([0-9a-f]{3}|[0-9a-f]{6})$/i', $color) !== 1) {
        return '#38bdf8';
    }

    if (strlen($color) === 4) {
        $color = sprintf('#%1$s%1$s%2$s%2$s%3$s%3$s', $color[1], $color[2], $color[3]);
    }

    return strtolower($color);
}

/**
 * Filter messages newer than a provided timestamp.
 *
 * @param array<int, array<string, mixed>> $messages
 * @return array<int, array<string, mixed>>
 */
function chat_messages_since(array $messages, float $since): array
{
    return array_values(array_filter(
        $messages,
        static fn(array $message): bool => ($message['timestamp'] ?? 0) > $since
    ));
}

/**
 * Build the active participant list based on recent messages.
 *
 * @param array<int, array<string, mixed>> $messages
 * @return array<int, array<string, mixed>>
 */
function chat_collect_participants(array $messages): array
{
    $activeSince = microtime(true) - CHAT_ACTIVE_WINDOW;
    $participants = [];

    foreach ($messages as $message) {
        $timestamp = $message['timestamp'] ?? 0;
        if ($timestamp < $activeSince) {
            continue;
        }

        $name = $message['author'] ?? 'Guest';
        $participants[$name] = [
            'name' => $name,
            'color' => $message['color'] ?? '#38bdf8',
            'lastActive' => $timestamp,
        ];
    }

    usort($participants, static fn(array $a, array $b): int => $b['lastActive'] <=> $a['lastActive']);

    return array_values($participants);
}

/**
 * Parse the inbound request body.
 *
 * @return array<string, mixed>
 */
function chat_request_payload(): array
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        return [];
    }

    if (!empty($_POST)) {
        return $_POST;
    }

    $contents = file_get_contents('php://input');
    if ($contents === false || $contents === '') {
        return [];
    }

    $data = json_decode($contents, true);
    if (is_array($data)) {
        return $data;
    }

    return [];
}

/**
 * Emit a JSON response and terminate execution.
 *
 * @param array<string, mixed> $payload
 */
function chat_send_json(array $payload, int $status = 200): void
{
    http_response_code($status);
    header('Content-Type: application/json');
    header('Cache-Control: no-store, must-revalidate');

    echo json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRESERVE_ZERO_FRACTION);
    exit;
}

/**
 * Handle AJAX-style request actions.
 */
function chat_handle_request(): void
{
    $action = $_GET['action'] ?? chat_request_payload()['action'] ?? null;
    if ($action === null) {
        return;
    }

    if ($action === 'messages') {
        $since = isset($_GET['since']) ? (float) $_GET['since'] : 0.0;
        $messages = chat_read_messages();
        $updates = $since > 0 ? chat_messages_since($messages, $since) : $messages;
        $latest = empty($messages) ? 0 : (float) end($messages)['timestamp'];

        chat_send_json([
            'messages' => $updates,
            'latest' => $latest,
            'participants' => chat_collect_participants($messages),
            'serverTime' => round(microtime(true), 3),
        ]);
    }

    if ($action === 'send') {
        $payload = chat_request_payload();
        $name = chat_sanitize_name($payload['name'] ?? '');
        $message = chat_sanitize_message($payload['message'] ?? '');
        $color = chat_validate_color($payload['color'] ?? '');

        if ($message === '') {
            chat_send_json(['error' => 'Message cannot be empty.'], 422);
        }

        $saved = chat_append_message($name, $message, $color);

        chat_send_json(['message' => $saved]);
    }

    chat_send_json(['error' => 'Unsupported action.'], 400);
}

chat_handle_request();

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Realtime Lounge</title>
  <style>
    :root {
      color-scheme: dark;
      --bg: #050b16;
      --panel: rgba(12, 20, 39, 0.92);
      --panel-border: rgba(94, 106, 128, 0.35);
      --muted: #94a3b8;
      --accent: #38bdf8;
      --danger: #f87171;
      font-family: "Inter", "Segoe UI", system-ui, -apple-system, sans-serif;
    }

    * {
      box-sizing: border-box;
    }

    body {
      margin: 0;
      min-height: 100vh;
      background: radial-gradient(circle at top, rgba(15, 23, 42, 0.92), var(--bg));
      color: #e2e8f0;
      display: flex;
      align-items: stretch;
      justify-content: center;
      padding: clamp(1.5rem, 3vw, 3.5rem);
    }

    main {
      display: grid;
      gap: 1.75rem;
      width: min(1100px, 100%);
    }

    .card {
      background: var(--panel);
      border: 1px solid var(--panel-border);
      border-radius: 20px;
      padding: clamp(1.5rem, 2vw, 2.25rem);
      box-shadow: 0 30px 80px rgba(10, 20, 40, 0.45);
    }

    header {
      display: flex;
      flex-wrap: wrap;
      justify-content: space-between;
      gap: 1rem;
      align-items: flex-end;
    }

    header h1 {
      margin: 0;
      font-size: clamp(2rem, 3vw, 2.8rem);
      letter-spacing: 0.02em;
    }

    header p {
      margin: 0.25rem 0 0;
      color: var(--muted);
      max-width: 480px;
      line-height: 1.6;
    }

    .presence {
      display: flex;
      flex-direction: column;
      gap: 0.65rem;
      margin-top: 1rem;
    }

    .presence h2 {
      margin: 0;
      font-size: 0.85rem;
      letter-spacing: 0.12em;
      text-transform: uppercase;
      color: var(--muted);
    }

    .presence-list {
      display: flex;
      gap: 0.75rem;
      flex-wrap: wrap;
    }

    .presence-pill {
      padding: 0.45rem 0.9rem;
      border-radius: 999px;
      background: rgba(148, 163, 184, 0.12);
      border: 1px solid rgba(148, 163, 184, 0.18);
      font-size: 0.85rem;
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    .presence-pill span {
      display: inline-block;
      width: 0.6rem;
      height: 0.6rem;
      border-radius: 50%;
    }

    .chat-shell {
      display: grid;
      grid-template-columns: 1fr min(280px, 35%);
      gap: 1.5rem;
    }

    .chat-window {
      display: flex;
      flex-direction: column;
      gap: 1rem;
      min-height: 460px;
    }

    .transcript {
      flex: 1;
      overflow-y: auto;
      padding: 1rem 1.1rem;
      border-radius: 16px;
      background: rgba(2, 6, 23, 0.55);
      border: 1px solid rgba(255, 255, 255, 0.06);
      display: flex;
      flex-direction: column;
      gap: 1rem;
      scroll-behavior: smooth;
    }

    .message {
      display: grid;
      gap: 0.25rem;
      padding: 0.75rem 0.85rem;
      border-radius: 14px;
      background: rgba(15, 23, 42, 0.6);
      border: 1px solid rgba(148, 163, 184, 0.15);
      box-shadow: 0 12px 30px rgba(8, 16, 35, 0.35);
    }

    .message header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 0.75rem;
      font-size: 0.85rem;
    }

    .message header h3 {
      margin: 0;
      font-size: 0.95rem;
      letter-spacing: 0.02em;
    }

    .message header time {
      color: var(--muted);
      font-size: 0.8rem;
    }

    .message .body {
      font-size: 1rem;
      line-height: 1.6;
      white-space: pre-line;
    }

    .composer {
      display: grid;
      gap: 1rem;
      border-radius: 18px;
      padding: 1.25rem;
      background: rgba(2, 6, 23, 0.6);
      border: 1px solid rgba(255, 255, 255, 0.07);
    }

    .composer form {
      display: grid;
      gap: 0.85rem;
    }

    .fields {
      display: grid;
      gap: 0.85rem;
      grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
    }

    label {
      font-size: 0.75rem;
      letter-spacing: 0.12em;
      text-transform: uppercase;
      color: var(--muted);
      display: block;
      margin-bottom: 0.4rem;
    }

    input[type="text"],
    textarea,
    input[type="color"] {
      width: 100%;
      border-radius: 12px;
      border: 1px solid rgba(148, 163, 184, 0.25);
      background: rgba(15, 23, 42, 0.6);
      color: inherit;
      font: inherit;
      padding: 0.75rem 0.85rem;
      transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }

    textarea {
      resize: vertical;
      min-height: 110px;
      line-height: 1.5;
    }

    input:focus,
    textarea:focus {
      outline: none;
      border-color: var(--accent);
      box-shadow: 0 0 0 3px rgba(56, 189, 248, 0.25);
    }

    button {
      border: none;
      border-radius: 999px;
      padding: 0.85rem 1.8rem;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.08em;
      cursor: pointer;
      background: linear-gradient(135deg, var(--accent), #2563eb);
      color: #0f172a;
      box-shadow: 0 16px 35px rgba(37, 99, 235, 0.35);
      transition: transform 0.2s ease, box-shadow 0.2s ease;
      justify-self: flex-start;
    }

    button:hover {
      transform: translateY(-2px);
      box-shadow: 0 22px 40px rgba(37, 99, 235, 0.45);
    }

    .sidebar {
      display: flex;
      flex-direction: column;
      gap: 1rem;
    }

    .sidebar section {
      padding: 1.25rem;
      background: rgba(15, 23, 42, 0.6);
      border: 1px solid rgba(148, 163, 184, 0.18);
      border-radius: 18px;
      display: grid;
      gap: 0.6rem;
      font-size: 0.95rem;
      line-height: 1.6;
    }

    ul {
      margin: 0;
      padding-left: 1.15rem;
      color: var(--muted);
      display: grid;
      gap: 0.35rem;
    }

    li {
      list-style: disc;
    }

    .status-banner {
      display: flex;
      align-items: center;
      gap: 0.75rem;
      border-radius: 14px;
      padding: 0.9rem 1.1rem;
      background: rgba(56, 189, 248, 0.12);
      border: 1px solid rgba(56, 189, 248, 0.35);
      color: var(--accent);
      font-size: 0.9rem;
      font-weight: 600;
    }

    .error {
      background: rgba(248, 113, 113, 0.12);
      border-color: rgba(248, 113, 113, 0.35);
      color: var(--danger);
    }

    footer {
      color: var(--muted);
      font-size: 0.85rem;
      display: flex;
      gap: 0.75rem;
      flex-wrap: wrap;
      justify-content: space-between;
    }

    footer span {
      display: inline-flex;
      gap: 0.25rem;
      align-items: center;
    }

    @media (max-width: 960px) {
      .chat-shell {
        grid-template-columns: 1fr;
      }

      .sidebar {
        order: -1;
      }
    }

    @media (max-width: 640px) {
      body {
        padding: 1rem;
      }

      header {
        flex-direction: column;
        align-items: flex-start;
      }

      .presence-list {
        flex-direction: column;
      }
    }
  </style>
</head>
<body>
  <main>
    <section class="card">
      <header>
        <div>
          <h1>Realtime Lounge</h1>
          <p>Share updates and collaborate with others in realtime. Messages synchronise automatically, and your preferences stay local to your device.</p>
        </div>
        <div class="status-banner" id="connectionBanner">🟢 Live connection</div>
      </header>
      <div class="presence">
        <h2>Active right now</h2>
        <div class="presence-list" id="presenceList"></div>
      </div>
    </section>

    <section class="card chat-shell">
      <div class="chat-window">
        <div class="transcript" id="transcript" aria-live="polite"></div>
        <div class="composer">
          <form id="composerForm">
            <div class="fields">
              <div>
                <label for="displayName">Display name</label>
                <input type="text" id="displayName" name="name" maxlength="32" placeholder="Pick a nickname">
              </div>
              <div>
                <label for="accentColor">Accent colour</label>
                <input type="color" id="accentColor" name="color" value="#38bdf8">
              </div>
            </div>
            <div>
              <label for="messageBody">Message</label>
              <textarea id="messageBody" name="message" placeholder="Type your message and press send" required></textarea>
            </div>
            <button type="submit">Send message</button>
          </form>
          <div class="status-banner error" id="formError" hidden>Unable to send your message. Try again.</div>
        </div>
      </div>
      <aside class="sidebar">
        <section>
          <strong>Tips</strong>
          <ul>
            <li>Messages update automatically every few seconds.</li>
            <li>Stay visible to others by posting at least once every five minutes.</li>
            <li>Your display name and colour are stored locally so you can pick up where you left off.</li>
          </ul>
        </section>
        <section>
          <strong>Shortcuts</strong>
          <ul>
            <li><kbd>Shift + Enter</kbd> adds a new line.</li>
            <li><kbd>Enter</kbd> sends your message.</li>
            <li>Keep it friendly and respectful — messages are public to everyone here.</li>
          </ul>
        </section>
      </aside>
    </section>

    <footer>
      <span id="messageCount">0 messages</span>
      <span id="serverClock">—</span>
    </footer>
  </main>

  <script>
    const state = {
      latest: 0,
      polling: null,
      user: {
        name: 'Guest',
        color: '#38bdf8'
      }
    };

    const transcript = document.getElementById('transcript');
    const composerForm = document.getElementById('composerForm');
    const nameInput = document.getElementById('displayName');
    const colorInput = document.getElementById('accentColor');
    const messageInput = document.getElementById('messageBody');
    const presenceList = document.getElementById('presenceList');
    const connectionBanner = document.getElementById('connectionBanner');
    const formError = document.getElementById('formError');
    const serverClock = document.getElementById('serverClock');
    const messageCount = document.getElementById('messageCount');

    function loadPreferences() {
      try {
        const stored = JSON.parse(localStorage.getItem('chat-preferences'));
        if (stored && stored.name) {
          state.user.name = stored.name;
          nameInput.value = stored.name;
        }
        if (stored && stored.color) {
          state.user.color = stored.color;
          colorInput.value = stored.color;
        }
      } catch (error) {
        console.warn('Unable to load saved preferences', error);
      }
    }

    function savePreferences() {
      const payload = {
        name: state.user.name,
        color: state.user.color
      };
      localStorage.setItem('chat-preferences', JSON.stringify(payload));
    }

    function updateStatus(online) {
      if (online) {
        connectionBanner.textContent = '🟢 Live connection';
        connectionBanner.classList.remove('error');
      } else {
        connectionBanner.textContent = '⚠️ Reconnecting…';
        connectionBanner.classList.add('error');
      }
    }

    function renderMessage(message) {
      const wrapper = document.createElement('article');
      wrapper.className = 'message';
      wrapper.dataset.messageId = message.id;

      const header = document.createElement('header');
      const name = document.createElement('h3');
      name.textContent = message.author || 'Guest';
      name.style.color = message.color || '#38bdf8';

      const time = document.createElement('time');
      const date = new Date((message.timestamp || 0) * 1000);
      time.textContent = date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });

      header.appendChild(name);
      header.appendChild(time);

      const body = document.createElement('div');
      body.className = 'body';
      body.innerText = message.message || '';

      wrapper.appendChild(header);
      wrapper.appendChild(body);

      return wrapper;
    }

    function renderMessages(messages) {
      const fragment = document.createDocumentFragment();
      messages.forEach((message) => {
        fragment.appendChild(renderMessage(message));
      });
      transcript.appendChild(fragment);
      transcript.scrollTop = transcript.scrollHeight;
    }

    function replaceTranscript(messages) {
      transcript.innerHTML = '';
      renderMessages(messages);
    }

    function renderPresence(participants) {
      presenceList.innerHTML = '';
      if (!participants.length) {
        const empty = document.createElement('div');
        empty.className = 'presence-pill';
        empty.textContent = 'Nobody is active right now';
        presenceList.appendChild(empty);
        return;
      }

      participants.forEach((participant) => {
        const pill = document.createElement('div');
        pill.className = 'presence-pill';

        const indicator = document.createElement('span');
        indicator.style.background = participant.color || '#38bdf8';

        const label = document.createElement('span');
        label.textContent = participant.name;

        pill.appendChild(indicator);
        pill.appendChild(label);
        presenceList.appendChild(pill);
      });
    }

    function setMessageCount(count) {
      messageCount.textContent = `${count} message${count === 1 ? '' : 's'}`;
    }

    async function fetchMessages(initial = false) {
      const params = new URLSearchParams({ action: 'messages' });
      if (!initial && state.latest) {
        params.set('since', state.latest);
      }

      try {
        const response = await fetch(`chat.php?${params.toString()}`, { cache: 'no-store' });
        if (!response.ok) {
          throw new Error('Request failed');
        }
        const payload = await response.json();
        updateStatus(true);
        formError.hidden = true;

        const { messages = [], latest = state.latest, participants = [], serverTime } = payload;

        if (initial) {
          replaceTranscript(messages);
          setMessageCount(messages.length);
        } else if (messages.length) {
          renderMessages(messages);
          setMessageCount(parseInt(messageCount.textContent, 10) + messages.length);
        }

        if (messages.length) {
          state.latest = messages[messages.length - 1].timestamp;
        } else {
          state.latest = latest;
        }

        renderPresence(participants);

        if (typeof serverTime === 'number') {
          const date = new Date(serverTime * 1000);
          serverClock.textContent = `Server time ${date.toLocaleTimeString()}`;
        }
      } catch (error) {
        console.error('Unable to fetch messages', error);
        updateStatus(false);
      }
    }

    async function sendMessage(event) {
      event.preventDefault();
      const message = messageInput.value.trim();
      if (!message) {
        messageInput.focus();
        return;
      }

      state.user.name = nameInput.value.trim() || 'Guest';
      state.user.color = colorInput.value || '#38bdf8';
      savePreferences();

      const payload = {
        action: 'send',
        name: state.user.name,
        color: state.user.color,
        message
      };

      try {
        const response = await fetch('chat.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(payload)
        });

        if (!response.ok) {
          throw new Error('Unable to send');
        }

        const { message: saved } = await response.json();
        formError.hidden = true;
        messageInput.value = '';
        messageInput.focus();
        renderMessages([saved]);
        setMessageCount(parseInt(messageCount.textContent, 10) + 1);
        state.latest = saved.timestamp;
      } catch (error) {
        console.error('Message send failed', error);
        formError.hidden = false;
      }
    }

    composerForm.addEventListener('submit', sendMessage);
    nameInput.addEventListener('change', () => {
      state.user.name = nameInput.value.trim() || 'Guest';
      savePreferences();
    });
    colorInput.addEventListener('change', () => {
      state.user.color = colorInput.value || '#38bdf8';
      savePreferences();
    });

    messageInput.addEventListener('keydown', (event) => {
      if (event.key === 'Enter' && !event.shiftKey) {
        event.preventDefault();
        composerForm.requestSubmit();
      }
    });

    loadPreferences();
    fetchMessages(true);
    state.polling = setInterval(() => fetchMessages(false), 5000);
  </script>
</body>
</html>
