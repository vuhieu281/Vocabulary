# Techbook - Used Electronics Service Reviews

A web platform for reviewing and discussing used electronics services in Vietnam. Users can share their experiences, rate services, and engage in discussions about various electronics service providers.

## Features

- User authentication (login/register)
- Service listings and details
- Review system with 5-star ratings
- Image upload for reviews
- Discussion forum for each service
- User profiles
- Admin panel for content moderation

## Requirements

- PHP 7.4 or higher
- MySQL 5.7 or higher
- XAMPP (or similar local development environment)

## Installation

1. Clone this repository to your XAMPP's htdocs directory:

```bash
git clone https://github.com/yourusername/techbook.git
cd techbook
```

2. Create a MySQL database named `techbook_db` (this will be done automatically when you access the website)

3. Initialize the database by visiting:

```
http://localhost/techbook/init_db.php
```

4. Access the website:

```
http://localhost/techbook
```

## Default Admin Account

- Username: admin
- Password: admin123

Please change these credentials after first login.

## Directory Structure

```
techbook/
├── assets/
│   ├── css/
│   ├── js/
│   └── images/
├── config/
│   └── database.php
├── includes/
│   ├── header.php
│   └── footer.php
├── templates/
├── index.php
├── login.php
├── register.php
├── init_db.php
└── README.md
```

## Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## License

This project is licensed under the MIT License - see the LICENSE file for details.

## Support

For support, please open an issue in the GitHub repository or contact the maintainers.
