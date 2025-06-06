## What is this project about?

A simple web-based chat application where users can create or join **public** and **private** chat rooms to communicate in real-time.

## Features

- User authentication (Sign up / Log in)
- Browse and join all available chat rooms
- Create new chat rooms (public or password-protected private rooms)
- Real-time messaging in chat rooms

## Setting up

## 1. Requirements
- Node.js
- npm (included with Node.js)
- XAMPP + phpMyAdmin (or any MySQL server)

## 2. Database Setup

1. Open phpMyAdmin
2. Create a new database called `chathub`
3. Open the `schema.sql` file included in this repository  
4. Run the SQL queries to create the necessary tables

## 3. Terminal Setup

In the terminal, inside the project folder, run the following commands:

```bash
node server.js
