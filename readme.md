# Course Discovery – WordPress Assignment

This project implements a Course Discovery system using WordPress with advanced filtering logic.

## Features

- Course search with multiple filters
- Providers filter
- Location filter
- Category filter
- Start Date filter
- Reset filters
- Responsive course grid UI
- Keyboard accessible filters
- Semantic HTML markup

## Filter Logic

Top level filters use **AND logic**

Values within the same filter use **OR logic**

Example:

(provider = UOSD OR provider = DMU)
AND
(location = India OR location = China)
AND
(category = Graphic Design)

## Post Types

The system supports:

- Courses
- Providers
- Instructors
## Setup Instructions

1. Install WordPress locally.

2. Clone repository into wp-content/themes/

3. Activate theme:

Appearance → Themes → Course Theme

4. Import database file:

database/course-discovery.sql
4. Update database connection

Edit:


wp-config.php


Set database credentials.


5. Install Advanced Custom Fields plugin.

6. Visit:

/courses


5. Run project


http://localhost/course-discovery
## Demo Access

### Frontend
http://your-project-url.com

### WordPress Admin
http://your-project-url.com/wp-admin

Username: pinki  
Password: DRftgyhu12

## UI Preview

### Course Filter Page

![Course Finder UI](screenshots/course-filter-ui.png)

## Accessibility
