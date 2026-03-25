Foi criado dois arquivos login.php (pages: front para o usuario apenas e manda para actions, actions: redirecionar e confirmar para home.php)

Index.php: apenas redireciona para home.php se ja estiver logado, se não manda direto para o login.php
auth.php: proteção das pages!
logout.php: função sair e voltar para o login REAL, se tentar voltar sem o login nao consegue! 

Importante! : tentar usar alguma criptografia basica no projeto (ex: sha1, md5)

Passo 1: apenas desenvolver a tela de login para o usuario em login.html para ver o andamento e depois voltar para .php para rodar projeto local com docker.