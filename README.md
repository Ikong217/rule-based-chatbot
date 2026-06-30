# NexaBot (Rule-Based Chatbot Framework)

A modular, extensible rule-based chatbot framework built with Laravel.

This project explores conversational system architecture using object-oriented design rather than artificial intelligence or machine learning. The chatbot is composed of independent response modules that are automatically discovered at runtime, allowing new conversation domains to be added without modifying the chatbot core.

The goal of this project was to design a maintainable and reusable conversation engine that demonstrates concepts such as intent matching, pattern matching, conversation context, response routing, and extensibility.

---

# Motivation

Most educational chatbots rely heavily on long chains of conditional statements.

```php
if (...)
elseif (...)
elseif (...)
```

This project instead separates each conversation topic into its own response module.

```
Shipping
Greeting
Customer Service
Products
...
```

Adding new chatbot functionality simply requires creating a new response class.

No modifications to the chatbot core are required.

---

# Features

- Modular response architecture
- Automatic module discovery
- Intent matching
- Pattern matching with dynamic placeholders
- Conversation context management
- Context-aware responses
- Restrictable responses
- Response streaming (Server-Sent Events)
- Fluent response builder
- Extensible component design

---

# Architecture

```
                User Message
                     │
                     ▼
              BaseHandler
                     │
         ┌───────────┴───────────┐
         ▼                       ▼
 Trigger Detection       Conversation Context
         │                       │
         └───────────┬───────────┘
                     ▼
            Response Module
                     │
      ┌──────────────┼──────────────┐
      ▼              ▼              ▼
Pattern Match   Intent Match   Keyword Match
      │              │              │
      └──────────────┴──────────────┘
                     ▼
             Response Builder
                     ▼
              Final Response
```

---

# Project Structure

```
ChatBot
│
├── Contracts
│   ├── Interfaces
│   └── Traits
│
├── Dependencies
│   ├── Conversation
│   ├── IntentMatcher
│   ├── PatternMatcher
│   ├── ResponseBuilder
│   └── Utilities
│
├── Handlers
│   ├── BaseHandler
│   └── Response
│
└── Responses
    ├── GreetingResponse
    ├── ProductResponse
    ├── ShippingResponse
    ├── CustomerServiceResponse
    └── ...
```

---

# Core Concepts

## Automatic Module Discovery

Every response module is discovered automatically.

```
Responses/

↓

ShippingResponse.php
GreetingResponse.php
ProductResponse.php
...

↓

Automatically Registered
```

This removes the need for manual configuration whenever a new chatbot feature is added.

---

## Modular Response System

Every chatbot feature extends a single base class.

```php
class ShippingResponse extends Response
```

Each response module is responsible only for its own domain.

Examples:

- Shipping
- Products
- Greetings
- Customer Support

This keeps the chatbot organized and easy to maintain.

---

## Intent Matching

Different user inputs can resolve to the same intent.

Example

```
Track my package

Where is my order?

Find my shipment
```

↓

```
track
```

↓

```
trackIntent()
```

---

## Pattern Matching

Supports extracting dynamic information from user messages.

Example

```
How much is shipping to Japan?
```

Pattern

```
how much is shipping to {location}
```

Captured

```
location = Japan
```

↓

```
shippingCost("Japan")
```

---

## Conversation Context

The chatbot remembers the current conversation topic.

Example

```
User:
I have a shipping question.

Bot:
Sure! What would you like to know?

User:
How long does it take?

Bot:
3–5 business days.
```

Since the conversation context is preserved, the chatbot understands that "How long does it take?" refers to shipping.

---

## Fluent Response Builder

Instead of manually concatenating strings, responses are built fluently.

```php
ResponseBuilder::start()
    ->line("Shipping Information")
    ->list([
        "Standard",
        "Express",
    ])
    ->closing()
    ->build();
```

This keeps response formatting separate from business logic.

---

## Response Restrictions

Modules may define their own restrictions before generating a response.

Examples include:

- Authentication
- Permissions
- Conversation state
- Custom validation

Restrictions are implemented independently from the response logic.

---

# Example Response Module

```php
class ShippingResponse extends Response
{
    public static array $triggerWords = [
        'shipping',
        'delivery',
        'track'
    ];
}
```

Creating a new chatbot feature simply means creating another response class.

---

# Design Philosophy

This project was inspired by Laravel's modular architecture.

Instead of building one large chatbot class, responsibilities are divided into independent components that can evolve separately.

The design emphasizes:

- Separation of concerns
- Reusability
- Extensibility
- Object-oriented programming
- Clean architecture

While several implementation ideas were inspired by Laravel's conventions, the chatbot architecture and conversation components were designed as an educational exploration of modular software design.

---

# Technologies

- PHP
- Laravel
- Object-Oriented Programming
- Interfaces
- Traits
- Server-Sent Events (SSE)

---

# Future Improvements

- Confidence scoring for intents
- Regular expression patterns
- Plugin architecture
- Database-backed knowledge base
- Semantic similarity matching
- Machine learning integration
- Large Language Model (LLM) support
- Multi-language conversations

---

# Purpose

This project was created to better understand conversational system design by implementing the core concepts behind chatbot frameworks rather than relying on existing libraries.

The emphasis of this project is not artificial intelligence itself, but building a maintainable and extensible software architecture capable of supporting conversational applications.
