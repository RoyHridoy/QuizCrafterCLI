# AI-Powered CLI Quiz App

An AI-powered quiz application built with Laravel, allowing users to test their knowledge across various topics with adjustable difficulty and question limits via the command line.

## Features

- **Topic Selection:** Choose from multiple quiz topics.
- **Customizable Quiz:** Set the number of questions (3 to 15).
- **Difficulty Levels:** Select between beginner, intermediate, and advanced.
- **AI Feedback:** Receive detailed feedback on your performance after the quiz.
- **Interactive CLI:** Smooth command-line interface for seamless user experience.

## Installation

Ensure you have **PHP 8.2+**, **Laravel 12**, and **Composer** installed on your system.

1. **Clone the repository:**

```bash
git clone https://github.com/RoyHridoy/QuizCrafterCLI.git
cd QuizCrafterCLI
```

2. **Install dependencies:**

```bash
composer install
```

3. **Set up environment:**

```bash
cp .env.example .env
```

4. **Configure AI Service:**

Ensure you have the required OPEN AI API credentials in your `.env` file:

```
OPENAI_API_KEY=your-ai-api-key
```

## Usage

1. **Start the quiz:**

```bash
php artisan quiz:start
```

2. **Follow the prompts to:**
   - Choose a quiz topic.
   - Select difficulty (beginner, intermediate, advanced).
   - Set the number of questions (3 to 15).

3. **Receive Feedback:**

After completing the quiz, you'll get a performance summary and suggestions for improvement.

## Contribution

Contributions are welcome! Follow these steps:

1. Fork the repository.
2. Create a new branch (`feature/new-feature`).
3. Commit your changes with descriptive messages.
4. Submit a pull request.

## License

This project is licensed under the MIT License.
