# DevTrack API

This is the Laravel 12 API backend for the DevTrack platform. It manages authentication, job searching, job tracking, applications, and dashboard statistics.

## Database Schema & Models

* **`User`**: Handles authentication, registration, and user profiles.
* **`Job`**: Represents job listings.
* **`SavedJob`**: Tracks job listings saved by users.
* **`Application`**: Manages job application progress, statuses, and history.

## API Endpoints

### Public Endpoints
* **`POST /api/register`**: Register a new user.
* **`POST /api/register/send-code`**: Send email verification code.
* **`POST /api/register/verify-code`**: Verify the registration code.
* **`POST /api/login`**: Authenticate and retrieve a Sanctum token.

### Authenticated Endpoints (Sanctum)
* **`POST /api/logout`**: Terminate the current session.
* **`GET /api/user`**: Retrieve logged-in user profile details.
* **`GET /api/jobs/search`**: Search & filter external and internal job listings.
* **`GET /api/jobs/{id}`**: Retrieve specific job details.
* **`GET /api/saved-jobs`**: List all saved jobs.
* **`POST /api/saved-jobs`**: Save a new job.
* **`DELETE /api/saved-jobs/{id}`**: Remove a saved job.
* **`GET /api/applications`**: List all submitted/tracked applications.
* **`POST /api/applications`**: Track a new job application.
* **`PUT /api/applications/{id}`**: Update application status (e.g. Applied, Interviewing, Offered, Rejected).
* **`DELETE /api/applications/{id}`**: Remove a tracked application.
* **`GET /api/dashboard/stats`**: Get overview metrics for applications.

## Local Development Setup

To run this API standalone:

1. Ensure your `.env` file is configured with the correct MySQL credentials.
2. Run database migrations:
   ```bash
   php artisan migrate
   ```
3. Run seeders to populate initial test data:
   ```bash
   php artisan db:seed
   ```
4. Start the development server:
   ```bash
   php artisan serve
   ```
