select * from usuario;
update usuario set admin = true where id_usuario = 1; -- setar usuário como admin

-- habilita pgcrypto (necessário para crypt/gen_salt)
CREATE EXTENSION IF NOT EXISTS pgcrypto;
-- criar 100 usuários para teste
DO $$
BEGIN
  FOR i IN 1..100 LOOP
    INSERT INTO usuario (nome, email, senha, telefone, admin)
    VALUES (
      'Usuário ' || i,
      'usuario' || i || '@teste.com',
      crypt('senhaPadrao123', gen_salt('bf')),  -- senha criptografada com bcrypt
      '1199999' || lpad(i::text, 3, '0'),
      (i % 10 = 0)  -- expressão que retorna BOOLEAN
    );
  END LOOP;
END;
$$;