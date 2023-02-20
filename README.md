
# qa-system-example-of-implementation

[ru]

Вопросно-ответная система основанная на расстоянии Левенштейна (fuzzywuzzy). Пример решения для сравнения удобства работы между обучением нейронной сети и алгоритмом

[en]

Question-response system based on the distance of Levenstein (fuzzywuzzy). An example solution for comparing the convenience of work between neural network learning and an algorithm


# Usage

```http
  GET /question
```

| Parameter | Type     | Description                |
| :-------- | :------- | :------------------------- |
| `query`   | `string` | **Required**. query to the program |


```http
  GET /answer
```

| Parameter | Type     | Description                |
| :-------- | :------- | :------------------------- |
| `question`   | `string` | **Required**. question to the program |




Question example:

```http
  GET /question?query=what about system
```

    Request -> /question?query=what about system
    Response <- {
            "ok": 200,
            "result": [
                "qa_system_info"
            ]
        }

OR 

    Request -> /question?query=how make to login after signup
    Response <- {
            "ok": 200,
            "result": [
                {
                    "code": "signup_help",
                    "percent": 50
                },
                {
                    "code": "signin_help",
                    "percent": 61
                }
            ]
        }

Answer example:

    Request -> /answer?question=qa_system_info
    Response <- {
            "ok": 200,
            "result": [
                "What about system?"
            ]
        }

UI:
    /ui/qa/learning
