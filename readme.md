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

## Installation

1. Clone repository


git clone https://github.com/pinkiyadav/course-discovery.git


2. Place project in XAMPP htdocs


C:\xampp\htdocs\course-discovery


3. Import database

Open **phpMyAdmin**

Import file:


database/course-discovery.sql


4. Update database connection

Edit:


wp-config.php


Set database credentials.

5. Run project


http://localhost/course-discovery

## UI Preview

### Course Filter Page

![Course Finder UI](screenshots/course-filter-ui.png)

## Accessibility
