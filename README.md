## Sistema de Gerenciamento de Matrículas

# Usuários:

[criar]:
    {
        "name": "Post Man",
        "email": "postman@test.com",
        "password": "senha123456",
        "password_confirmation": "senha123456",
        "_token": "CBIKPLmMucwUiu66nv1WyWMiTkdP44OBcJtcqKR1"
    }

    Adicionar em verifyCsrfToken:

    protected $except = [
        '/register'
    ];

