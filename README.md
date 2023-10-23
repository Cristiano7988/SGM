## Sistema de Gerenciamento de Matrículas [WEB]

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

## Sistema de Gerenciamento de Matrículas [API]

# Usuários:

[Criar]:
    name
    email
    password

[Ver]:
    id

[Editar]:
    name
    email
    password

[Deletar]:
    id
