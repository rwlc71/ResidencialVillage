<?php

if (!isset($_COOKIE['usuario'])) {

    echo "<meta http-equiv='refresh' content='0; URL=autentica.php'>
      <script type=\"text/javascript\">
      alert(\"É preciso estar autenticado para acessar o conteúdo da pagina!\");
      </script>
     ";
    RETURN DIE;
} 