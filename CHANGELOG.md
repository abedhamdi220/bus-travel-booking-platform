# Changelog

All notable changes to this project will be documented in this file.

## [Unreleased] - 2026-08-22

### Added
- **MVCS Architecture Pattern**: Introduced `CompanyService` in `app/Services` to handle complex business logic related to trips, metrics, and profiles, separating concerns from `CompanyController`.

### Changed
- **Refactored `CompanyController`**:
  - Injected `CompanyService` via constructor dependency injection.
  - Moved `createTripWithSeats`, `getTopRouteThisMonth`, `getCompanyMetrics`, and `getCompanyProfile` logic to the new service class.
  - Reduced controller size and improved code maintainability and testability.
- **Views Updates**: Minor structural and formatting adjustments in Blade views (`brez.html`, `company-bus-add.blade.php`, `company-buses.blade.php`, `companies.blade.php`, `admin-profile.blade.php`).
- **Code Quality**: Addressed minor syntax and formatting issues across various controllers (`AdminController`, `DriverController`, `ProfileDriverController`, `SeatsController`) and models (`Review`).
- **Database Migrations**: Minor adjustments to `ads_logs` and `driver_locations` migration files to ensure consistency.

### Fixed
- **Critical Syntax Error**: Resolved a PHP Parse error in `CompanyController` caused by improper method definition and scope resolution during the initial MVCS refactoring attempt. All PHP files are now verified to pass syntax linting.
