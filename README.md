Question exqmple:
    Request -> /question?query=what about system
    Response -> {
            "ok": 200,
            "result": [
                "qa_system_info"
            ]
        }

    OR 

    Request -> /question?query=how make to login after signup
    Response -> {
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
    Response -> {
            "ok": 200,
            "result": [
                "What about system?"
            ]
        }

UI:
    /ui/qa/learning