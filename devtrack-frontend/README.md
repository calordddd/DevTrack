# DevTrack Frontend

This is the React client application for DevTrack, built on top of React 19, Vite, Tailwind CSS 4, Axios, and Lucide React.

## Directory Structure

* **`src/pages/`**: Complete user views for the web application:
  * `Login.jsx` & `Register.jsx`: Authentication views.
  * `Dashboard.jsx`: User overview panel.
  * `JobSearch.jsx`: Search & browse job listings.
  * `Applications.jsx`: Status tracking for applied jobs.
  * `SavedJobs.jsx`: Manage saved job listings.
* **`src/components/`**: Reusable UI elements:
  * `Layout.jsx`, `Navbar.jsx`, `Sidebar.jsx`: Base structure & navigation.
  * `JobCard.jsx`, `ApplicationCard.jsx`: Cards presenting lists of jobs/applications.
  * `DashboardStats.jsx`: Stats panel displaying dashboard statistics.
  * `Modal.jsx`, `LoadingSpinner.jsx`: General-purpose modals and loading indicators.
* **`src/services/`**: Integration services:
  * `api.js`: Axios instance client configured to fetch from the Laravel REST API backend.

## Available Scripts

In the `devtrack-frontend` directory, you can run:

### `npm run dev`
Runs the app in development mode at [http://localhost:5173](http://localhost:5173).

### `npm run build`
Builds the app for production in the `dist` folder.

### `npm run lint`
Runs ESLint to analyze static code warnings or issues.

### `npm run preview`
Preview the production build locally.
