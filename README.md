# Library Management System

## Overview

The Library Management System (LMS) is a web application built with Laravel that allows users to manage books, borrow them, and keep track of borrowing records. This system features user authentication, CRUD operations for books and users, a borrowing challenge, and additional functionalities like book filtering and a rating system.

## Features

- **User Authentication**: Secure login and registration using JWT tokens.
- **Books Management**: Full CRUD operations for books.
- **Users Management**: CRUD operations for user management with administrative permissions.
- **Borrowing System**: Borrow and return books with automatic due dates.
- **Book Filtering**: Search books by author, category, and availability.
- **Rating System**: Users can rate books and view ratings.

## Project Setup

### Requirements

- PHP >= 8.0
- Composer
- Laravel >= 9.x
- MySQL or another database

### Installation

1. **Clone the Repository**

    ```bash
    git clone https://github.com/Dralve/library-management-system.git
    ```

2. **Navigate to the Project Directory**

    ```bash
    cd library-management-system
    ```

3. **Install Dependencies**

    ```bash
    composer install
    ```

4. **Set Up Environment Variables**

    Copy the `.env.example` file to `.env` and configure your database and other environment settings.

    ```bash
    cp .env.example .env
    ```

    Update the `.env` file with your database credentials and other configuration details.


5. **Run Migrations**

    ```bash
    php artisan migrate
    ```

6. **Seed the Database (To Make Admin User)**

    ```bash
    php artisan db:seed
    ```

7. **Start the Development Server**

    ```bash
    php artisan serve
    ```

## API Endpoints

### Authentication

- **Login**: `POST /api/auth/login`
- **Register**: `POST /api/auth/register`
- **Logout**: `POST api/auth/logout`

### Books

- **Create Book**: `POST /api/v1/books`
- **View Books**: `GET /api/v1/books`
- **Update Book**: `PUT /api/v1/books/{id}`
- **Delete Book**: `DELETE /api/v1/books/{id}`

### Users

- **Create User**: `POST /api/v1/users`
- **View Users**: `GET /api/v1/users`
- **Update User**: `PUT /api/v1/user`
- **Delete User**: `DELETE /api/v1/users/{id}`
- **Update User Role**: `DELETE /api/v1/users/{id}/role`

### Borrow Records

- **Create Borrow Record**: `POST /api/borrow-records`
- **View Borrow Records**: `GET /api/borrow-records`
- **Update Borrow Record**: `PUT /api/borrow-records/{id}`
- **Delete Borrow Record**: `DELETE /api/borrow-records/{id}`

### Book Filtering

- **Filter Books**: `GET /api/v1/filter/books?author={author}&category={category}&available={available}`

### Ratings

- **Create Rating**: `POST /api/ratings`
- **View Ratings**: `GET /api/ratings/{id}`
- **Update Rating**: `PUT /api/ratings/{id}`
- **Delete Rating**: `DELETE /api/ratings/{id}`

### Categories

- **Create Category**: `POST /api/v1/categories`
- **View Category**: `GET /api/v1/categories`
- **Update Category**: `PUT /api/v1/categories/{id}`
- **Delete Category**: `DELETE /api/v1/categories/{id}`

## Validation Rules

- **BookFormRequest**: Validates book data including title, author, and description.
- **BorrowRecordFormRequest**: Ensures the validity of borrowing dates and return dates.

## Error Handling

Customized error messages and responses are provided to ensure clarity and user-friendly feedback.

## Documentation

All code is documented with appropriate comments and DocBlocks. For more details on the codebase, refer to the inline comments.

## Contributing

Contributions are welcome! Please follow the standard pull request process and adhere to the project's coding standards.

## License

This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for details.

## Contact

For any questions or issues, please contact [your email] or open an issue on GitHub.

