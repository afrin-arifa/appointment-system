# Doctor Appointment System

Laravel 13 appointment management for administrators, doctors, and patients.

## Features

- Role-aware authentication with login throttling and persistent sessions.
- Admin management of doctors, patients, appointment summaries, filtering, and pagination.
- Doctor availability schedules with overlap validation and appointment-preserving safeguards.
- Patient doctor search, slot-based booking, cancellations, and appointment history.
- Transactional booking protected by database uniqueness and queued email notifications.
- Responsive Bootstrap 5 interface.

## Local setup

1. Set database and mail/queue environment values in `.env`.
2. Run `php artisan migrate`.
3. Start `php artisan queue:work` to deliver queued appointment notifications.
4. Build assets with `npm run build` if your deployment pipeline requires it.

For automated tests, enable the PHP PDO SQLite extension and run `php artisan test`. The included test configuration uses an in-memory SQLite database.

## Production checklist

- Set `APP_ENV=production`, `APP_DEBUG=false`, and a secure application key.
- Configure a real `MAIL_MAILER`, queue worker supervisor, cache/session driver, and HTTPS.
- Run `php artisan config:cache`, `php artisan route:cache`, and `php artisan view:cache` during deployment.
- Create the first administrator through a controlled deployment process; do not use the example seed password in production.
