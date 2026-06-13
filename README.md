# DevTrack Workspace

Welcome to **DevTrack**, a job listing and tracking platform. This workspace consists of a Laravel API backend and a Vite-based React frontend.

---

## 📖 System Architecture & Guide

For a detailed walkthrough of the system architecture, database relationships, API endpoints, core workflows (e.g. Email Verification, Forgot Password), and frontend components, please refer to the:

👉 **[DevTrack System Guide (SYSTEM_GUIDE.md)](./SYSTEM_GUIDE.md)**

---

## Project Structure

- **[devtrack-api](file:///c:/Users/cedri/Desktop/DevTrack/devtrack-api)**: Laravel 12 backend serving REST API endpoints.
- **[devtrack-frontend](file:///c:/Users/cedri/Desktop/DevTrack/devtrack-frontend)**: React 19 + Vite + Tailwind CSS frontend application.

---

## Getting Started

### Prerequisites
1. **XAMPP / MySQL**: Ensure MySQL is running on port `3306`.
2. **PHP**: Version `^8.2`.
3. **Composer**: PHP dependency manager.
4. **Node.js**: Version `^20` or higher.

### Installation

To set up the workspace, run the following commands in their respective directories:

1. **Root Workspace**:
   ```bash
   npm install
   ```
2. **API Backend**:
   ```bash
   cd devtrack-api
   composer install
   npm install
   php artisan migrate --seed
   ```
3. **Frontend**:
   ```bash
   cd devtrack-frontend
   npm install
   ```

---

## Running the Application

To launch both the Laravel API server and the React frontend development server concurrently, run the following command from the workspace root:

* On **Windows PowerShell** (if execution policies are restricted):
  ```powershell
  npm.cmd run dev
  ```
* On standard terminals:
  ```bash
  npm run dev
  ```

Once started:
* **API Endpoints**: [http://127.0.0.1:8000](http://127.0.0.1:8000)
* **Frontend Web App**: [http://localhost:5173](http://localhost:5173)

---

## Core Technologies

* **Backend**: Laravel 12, Laravel Sanctum, SQLite/MySQL
* **Frontend**: React 19, React Router 7, Axios, Lucide React, Tailwind CSS 4
* **Build Tools**: Vite, Concurrently
