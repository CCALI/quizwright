# QuizWright
## A quiz builder and formative assessment tool for legal education

CALI QuizWright is a tool for creating and publishing formative assessments for your class. QuizWright gives law teachers a web-based platform to create and manage personal question banks and quizzes for their students. When used with CALI's AutoPublish, LessonLive, and LessonLink features, QuizWright quizzes can be delivered to students and taken in real time in the classroom using the familiar CALI Lesson Viewer.

## Session storage

QuizWright runs on more than one pod behind the load balancer, so PHP sessions cannot be stored on the local filesystem: whichever pod serves a request writes `$_SESSION` to its own disk, and the next request lands somewhere else and finds nothing. The symptom is a user who appears to log themselves out at random.

`includes/session.php` fixes this by pointing PHP's session save handler at the same memcached node SimpleSAMLphp uses. It must be the **first** `require` in every entry point, before any output: `php_value` directives in `.htaccess` are inert under PHP-FPM, so all session settings have to be applied from PHP, and they only take effect before `session_start()`. This is also why the memcached address cannot live in `config.php` — that file is loaded after the session is already open.

Instead, copy `includes/session-config.php.default` to `includes/session-config.php` (gitignored) and set `QW_MEMCACHED_HOST`, or set a `QW_MEMCACHED` environment variable in the PHP-FPM pool config. If both are set, the environment wins. **Every pod needs the same value and the `memcached` extension**, or the pods that don't will silently fall back to node-local sessions.

Nothing configured means no shared store: `session.php` logs a warning and sessions stay node-local. To confirm a deploy took, add a temporary `error_log(session_save_path() . ' / ' . ini_get('session.save_handler'));` after `session_start()` and make enough requests to land on every pod — each one must report `memcached` and the real address, not a filesystem path.
