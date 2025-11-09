<?php
    // ----------------------------------------------------
    // INICIO DE SESIÓN
    // ----------------------------------------------------
    session_start();


    $usuarios = [
        ["usuario" => "Miguel","password" => "1234", "rol" => "admin"],
        ["usuario" => "Rafelbunyol","password" => "0000", "rol" => "bibliotecario"],
        ["usuario" => "Victor","password" => "1111", "rol" => "lector"],
        ["usuario" => "Ana","password" => "2222", "rol" => "profesor"],
    ];

    $rolesPermisosBase = [
        'admin' => [
            'gestion_usuarios' => true,
            'ver_informe' => true,
            'configurar_app' => true,
            'catalogar' => true,
            'prestamos' => true,
            'reservas'  => true,
            'ver_novedades' => true,
            'ver_noticias' => true,
            'ver_libros_nuevos' => true,
        ],
        'bibliotecario' => [
            'gestion_usuarios'  => false,
            'ver_informe' => false,
            'configurar_app' => false,
            'catalogar' => true,
            'prestamos' => true,
            'reservas' => true,
            'ver_novedades' => true,
            'ver_noticias' => false,
            'ver_libros_nuevos' => true,
        ],
        'lector' => [
            'gestion_usuarios' => false,
            'ver_informe' => false,
            'configurar_app' => false,
            'catalogar' => false,
            'prestamos' => false,
            'reservas' => false,
            'ver_novedades' => true,
            'ver_noticias' => true,
            'ver_libros_nuevos' => true,
        ],
        'profesor' => [
            'gestion_usuarios' => false,
            'ver_informe' => false,
            'configurar_app' => false,
            'catalogar' => false,
            'prestamos' => false,
            'reservas' => true,
            'ver_novedades' => true,
            'ver_noticias' => true,
            'ver_libros_nuevos' => true,
        ],
    ];

    // Inicializa en sesión si no existe
    if (!isset($_SESSION['usuarios'])) {
        $_SESSION['usuarios'] = $usuarios;
    }
    if (!isset($_SESSION['rolesPermisos'])) {
        $_SESSION['rolesPermisos'] = $rolesPermisosBase;
    }

    $usuarios       = &$_SESSION['usuarios'];
    $rolesPermisos  = &$_SESSION['rolesPermisos'];

    // ----------------------------------------------------
    // FUNCIONES DE UTILIDAD
    // ----------------------------------------------------
    function normalizarRol($rol) {
        return strtolower(trim($rol));
    }

    function crearCookieUsuario($usuario, $rol) {
        $datos = json_encode(["usuario" => $usuario, "rol" => $rol], JSON_UNESCAPED_UNICODE);
        // 30 min de vida de cookie
        setcookie("auth", $datos, time() + 1800, "/");
    }

    function eliminarCookieUsuario() {
        setcookie("auth", "", time() - 3600, "/");
    }

    function permisosDeRol($rol, $rolesPermisos) {
        $rol = normalizarRol($rol);
        return $rolesPermisos[$rol] ?? [];
    }

    function tienePermiso($permisos, $permiso) {
        return !empty($permisos[$permiso]);
    }

    // --- (2) FUNCIÓN bienvenido --------------------------
    function bienvenido($usuario) {
        $usuario = htmlspecialchars($usuario);
        echo "<div class='banner'>👋 ¡Bienvenido/a, <strong>{$usuario}</strong>! Disfruta de la plataforma.</div>";
    }

    // Helpers de UI para cumplir (1): “enlaces, no texto plano”
    function enlaceOpcion($condicion, $texto, $action, $claseSi = 'ok', $claseNo = 'no') {
        $texto = htmlspecialchars($texto);
        $action = htmlspecialchars($action);
        if ($condicion) {
            // Enlace funcional a la misma página (acciones separadas por GET)
            return "<li class='{$claseSi}'><a href='?action={$action}'>{$texto}</a></li>";
        }
        return "<li class='{$claseNo}'><span>{$texto}</span></li>";
    }

    // --- (3) FUNCIÓN añadir usuario ----------------------
    function puedeAnadirUsuario($rolActual, $rolesPermisos) {
        return !empty(permisosDeRol($rolActual, $rolesPermisos)['gestion_usuarios']);
    }

    function anadirUsuario(&$usuarios, $nuevoUsuario, $password, $rol) {
        $rol = normalizarRol($rol);
        // Evitar duplicados por nombre exacto
        foreach ($usuarios as $u) {
            if (strcasecmp($u['usuario'], $nuevoUsuario) === 0) {
                return [false, "El usuario '{$nuevoUsuario}' ya existe."];
            }
        }
        $usuarios[] = ["usuario" => $nuevoUsuario, "password" => $password, "rol" => $rol];
        return [true, "Usuario '{$nuevoUsuario}' añadido correctamente con rol '{$rol}'."];
    }

    // --- Buscar usuario ----------------------
    function rolesQuePuedenBuscar() {
        // Puedes ajustar esta lista a tu política
        return ['admin', 'bibliotecario', 'profesor'];
    }

    function buscarUsuario($usuarios, $nombre) {
        foreach ($usuarios as $u) {
            if (strcasecmp($u['usuario'], $nombre) === 0) {
                return $u;
            }
        }
        return null;
    }

    // --- Modificar permisos --
    function puedeModificarPermisos($rolActual) {
        return $rolActual === 'admin';
    }

    $TODOS_PERMISOS = [
        'gestion_usuarios'  => 'Gestionar usuarios',
        'ver_informe'       => 'Ver informes del sistema',
        'configurar_app'    => 'Configurar la aplicación',
        'catalogar'         => 'Catalogar libros',
        'prestamos'         => 'Gestionar préstamos y devoluciones',
        'reservas'          => 'Administrar reservas',
        'ver_novedades'     => 'Novedades',
        'ver_noticias'      => 'Noticias',
        'ver_libros_nuevos' => 'Libros nuevos',
    ];

    // ----------------------------------------------------
    // LOGIN
    // ----------------------------------------------------
    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["__login"], $_POST["usuario"], $_POST["password"])) {
        $usuarioInput  = trim($_POST["usuario"]);
        $passwordInput = trim($_POST["password"]);

        $rolEncontrado = null;
        $usuarioValido = null;

        foreach ($usuarios as $u) {
            if ($u["usuario"] === $usuarioInput && $u["password"] === $passwordInput) {
                $rolEncontrado = normalizarRol($u["rol"]);
                $usuarioValido = $u["usuario"];
                break;
            }
        }

        if ($rolEncontrado) {
            $_SESSION["usuario"] = $usuarioValido;
            $_SESSION["rol"]     = $rolEncontrado;
            crearCookieUsuario($usuarioValido, $rolEncontrado);
            header("Location: ".$_SERVER['PHP_SELF']);
            exit;
        } else {
            $error = "❌ Usuario o contraseña incorrectos.";
        }
    }

    // ----------------------------------------------------
    // COOKIE DE SESIÓN
    // ----------------------------------------------------
    if (!isset($_SESSION["usuario"]) && isset($_COOKIE["auth"])) {
        $datos = json_decode($_COOKIE["auth"], true);
        if ($datos && isset($datos["usuario"], $datos["rol"])) {
            $_SESSION["usuario"] = $datos["usuario"];
            $_SESSION["rol"]     = normalizarRol($datos["rol"]);
            eliminarCookieUsuario();
        }
    }

    // ----------------------------------------------------
    // CERRAR SESIÓN
    // ----------------------------------------------------
    if (isset($_GET["logout"])) {
        session_unset();
        session_destroy();
        eliminarCookieUsuario();
        header("Location: ".$_SERVER['PHP_SELF']);
        exit;
    }

    // ----------------------------------------------------
    // ACCIONES para los enlaces del panel
    // ----------------------------------------------------
    $usuario = $_SESSION["usuario"] ?? null;
    $rol     = $_SESSION["rol"] ?? null;
    $permisos = $rol ? permisosDeRol($rol, $rolesPermisos) : [];

    $mensajeOK = null;
    $mensajeERR = null;

    $action = $_GET['action'] ?? null;

    // Procesamos POST de cada herramienta
    if ($usuario && $_SERVER['REQUEST_METHOD']==='POST') {

        // Añadir usuario
        if (isset($_POST['__add_user']) && puedeAnadirUsuario($rol, $rolesPermisos)) {
            $nu = trim($_POST['nuevo_usuario'] ?? '');
            $pw = trim($_POST['nuevo_password'] ?? '');
            $rr = normalizarRol($_POST['nuevo_rol'] ?? '');
            if ($nu === '' || $pw === '' || $rr === '') {
                $mensajeERR = "Completa usuario, contraseña y rol.";
            } else {
                [$ok, $msg] = anadirUsuario($usuarios, $nu, $pw, $rr);
                $mensajeOK = $ok ? $msg : null;
                $mensajeERR = $ok ? null : $msg;
            }
            $action = 'add_user';
        }

        // Buscar usuario (no cambia estado)
        if (isset($_POST['__search_user']) && in_array($rol, rolesQuePuedenBuscar(), true)) {
            $action = 'search_user';
        }

        // Modificar permisos
        if (isset($_POST['__save_perms']) && puedeModificarPermisos($rol)) {
            $rolEdit = normalizarRol($_POST['rol_editar'] ?? '');
            if (!isset($rolesPermisos[$rolEdit])) {
                $mensajeERR = "Rol no válido.";
            } else {
                // Por cada permiso, si no viene marcado => false
                $actual = $rolesPermisos[$rolEdit];
                $nuevos = [];
                foreach ($GLOBALS['TODOS_PERMISOS'] as $clave => $_) {
                    $nuevos[$clave] = isset($_POST['perm_'.$clave]) ? true : false;
                }
                $rolesPermisos[$rolEdit] = $nuevos;
                $mensajeOK = "Permisos del rol '{$rolEdit}' actualizados.";
            }
            $action = 'edit_perms';
        }
    }

    ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Roles + Sesiones + Permisos</title>
        <style>
            body { font-family: Arial, sans-serif; margin: 40px; }
            form { margin-bottom: 20px; }
            .admin { color: red; }
            .bibliotecario { color: green; }
            .lector { color: blue; }
            .profesor { color: purple; }
            .panel { border: 1px solid #ccc; padding: 10px; border-radius: 8px; margin-top: 10px; }
            .no { color:#999; text-decoration: line-through; }
            .ok { color:green; }
            ul { line-height:1.6; }
            .banner { background:#f5faff; border:1px solid #cde; padding:10px 12px; border-radius:8px; margin:10px 0 20px; }
            .msgok{background:#ecfff0;border:1px solid #9ad3a2;padding:10px;border-radius:8px;margin:10px 0;}
            .msger{background:#fff0f0;border:1px solid #e0a0a0;padding:10px;border-radius:8px;margin:10px 0;}
            .tools{border:1px dashed #aaa;padding:12px;border-radius:10px;margin-top:16px;}
            .tools h3{margin-top:0}
            label{display:block;margin-top:8px}
            input[type=text], input[type=password], select { padding:6px; width:280px; max-width:100%;}
            button{padding:8px 12px; cursor:pointer;}
            .grid { display:grid; grid-template-columns: repeat(auto-fit,minmax(220px,1fr)); gap:6px 18px; }
            .perm{display:flex; align-items:center; gap:6px;}
            a { text-decoration: none; }
            a:hover { text-decoration: underline; }
        </style>
    </head>
    <body>

    <h1>Aplicación con Roles usando Cookies y Sesiones</h1>

    <?php if (!$usuario): ?>
        <?php if (isset($error)) echo "<p class='msger'>".htmlspecialchars($error)."</p>"; ?>
        <form method="post" action="">
            <input type="hidden" name="__login" value="1">
            <label>Nombre de usuario:</label>
            <input type="text" name="usuario" required>

            <label>Contraseña:</label>
            <input type="password" name="password" required style="margin-bottom:12px;">

            <button type="submit">Iniciar sesión</button>
        </form>

    <?php else: ?>

        <?php bienvenido($usuario); // (2) mensaje de bienvenida ?>

        <p>Sesión iniciada como <strong><?php echo htmlspecialchars($usuario); ?></strong>
        (rol: <span class="<?php echo htmlspecialchars($rol); ?>"><?php echo htmlspecialchars($rol); ?></span>) ·
        <a href="?logout=1">Cerrar sesión</a></p>

        <?php if ($mensajeOK): ?><div class="msgok"><?php echo htmlspecialchars($mensajeOK); ?></div><?php endif; ?>
        <?php if ($mensajeERR): ?><div class="msger"><?php echo htmlspecialchars($mensajeERR); ?></div><?php endif; ?>

        <div class="panel">
            <h2>Panel (controlado por <em>permisos</em>)</h2>
            <ul>
                <!-- (1) Las opciones ahora son ENLACES cuando hay permiso -->
                <?php
                echo enlaceOpcion(tienePermiso($permisos,'gestion_usuarios'), 'Gestionar usuarios', 'add_user');
                echo enlaceOpcion(tienePermiso($permisos,'ver_informe'), 'Ver informes del sistema', 'ver_informe');
                echo enlaceOpcion(tienePermiso($permisos,'configurar_app'), 'Configurar la aplicación', 'configurar_app');

                echo enlaceOpcion(tienePermiso($permisos,'catalogar'), 'Catalogar libros', 'catalogar');
                echo enlaceOpcion(tienePermiso($permisos,'prestamos'), 'Gestionar préstamos y devoluciones', 'prestamos');
                echo enlaceOpcion(tienePermiso($permisos,'reservas'), 'Administrar reservas', 'reservas');

                echo enlaceOpcion(tienePermiso($permisos,'ver_novedades'), 'Novedades', 'novedades');
                echo enlaceOpcion(tienePermiso($permisos,'ver_noticias'), 'Noticias', 'noticias');
                echo enlaceOpcion(tienePermiso($permisos,'ver_libros_nuevos'), 'Libros nuevos', 'libros_nuevos');

                // Accesos a herramientas extra
                echo enlaceOpcion(in_array($rol, rolesQuePuedenBuscar(), true), 'Buscar usuario', 'search_user');
                echo enlaceOpcion(puedeModificarPermisos($rol), 'Modificar permisos (roles)', 'edit_perms');
                ?>
            </ul>
            <p style="color:#666;">* Las opciones tachadas no están disponibles para tu rol.</p>
        </div>

        <!-- Contenido de "cada archivo/enlace" según ?action=... -->
        <?php if ($action === 'add_user' && puedeAnadirUsuario($rol, $rolesPermisos)): ?>
            <div class="tools">
                <h3>➕ Añadir usuario</h3>
                <form method="post">
                    <input type="hidden" name="__add_user" value="1">
                    <label>Usuario</label>
                    <input type="text" name="nuevo_usuario" required>
                    <label>Contraseña</label>
                    <input type="password" name="nuevo_password" required>
                    <label>Rol</label>
                    <select name="nuevo_rol" required>
                        <option value="admin">admin</option>
                        <option value="bibliotecario">bibliotecario</option>
                        <option value="profesor">profesor</option>
                        <option value="lector">lector</option>
                    </select>
                    <div style="margin-top:10px;"><button type="submit">Crear usuario</button></div>
                </form>
            </div>
        <?php endif; ?>

        <?php if ($action === 'search_user' && in_array($rol, rolesQuePuedenBuscar(), true)): ?>
            <div class="tools">
                <h3>🔎 Buscar usuario</h3>
                <form method="post">
                    <input type="hidden" name="__search_user" value="1">
                    <label>Nombre de usuario</label>
                    <input type="text" name="buscar_nombre" required>
                    <div style="margin-top:10px;"><button type="submit">Buscar</button></div>
                </form>
                <?php
                if ($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['buscar_nombre'])) {
                    $res = buscarUsuario($usuarios, trim($_POST['buscar_nombre']));
                    if ($res) {
                        echo "<div class='msgok'><strong>Encontrado:</strong><br>Nombre: ".htmlspecialchars($res['usuario'])."<br>Contraseña: ".htmlspecialchars($res['password'])."<br>Rol: ".htmlspecialchars($res['rol'])."</div>";
                    } else {
                        echo "<div class='msger'>No existe un usuario con ese nombre.</div>";
                    }
                }
                ?>
            </div>
        <?php endif; ?>

        <?php if ($action === 'edit_perms' && puedeModificarPermisos($rol)): ?>
            <div class="tools">
                <h3>🛡️ Modificar permisos por rol</h3>
                <form method="post">
                    <label>Selecciona rol</label>
                    <select name="rol_editar" onchange="this.form.submit()">
                        <?php
                        $rolEditar = normalizarRol($_POST['rol_editar'] ?? 'admin');
                        foreach ($rolesPermisos as $rName => $_) {
                            $sel = $rName === $rolEditar ? "selected" : "";
                            echo "<option value='".htmlspecialchars($rName)."' $sel>".htmlspecialchars($rName)."</option>";
                        }
                        ?>
                    </select>
                </form>

                <?php
                if (isset($rolesPermisos[$rolEditar])) {
                    $permsRol = $rolesPermisos[$rolEditar];
                    ?>
                    <form method="post" style="margin-top:10px;">
                        <input type="hidden" name="__save_perms" value="1">
                        <input type="hidden" name="rol_editar" value="<?php echo htmlspecialchars($rolEditar); ?>">
                        <div class="grid">
                            <?php foreach ($TODOS_PERMISOS as $clave => $label): ?>
                                <label class="perm">
                                    <input type="checkbox" name="perm_<?php echo htmlspecialchars($clave); ?>" <?php echo !empty($permsRol[$clave]) ? 'checked' : ''; ?>>
                                    <?php echo htmlspecialchars($label); ?>
                                </label>
                            <?php endforeach; ?>
                        </div>
                        <div style="margin-top:12px;">
                            <button type="submit">Guardar cambios</button>
                        </div>
                    </form>
                    <?php
                } else {
                    echo "<div class='msger'>Rol no válido.</div>";
                }
                ?>
            </div>
        <?php endif; ?>

        <!-- Ejemplos de “páginas” enlazadas (placeholders simples) -->
        <?php
        $placeholders = [
            'ver_informe'   => 'Aquí verías los informes del sistema.',
            'configurar_app'=> 'Aquí podrías configurar parámetros de la aplicación.',
            'catalogar' => 'Interfaz para catalogar libros.',
            'prestamos' => 'Gestión de préstamos y devoluciones.',
            'reservas' => 'Panel para administrar reservas.',
            'novedades' => 'Sección de novedades.',
            'noticias' => 'Sección de noticias.',
            'libros_nuevos' => 'Listado de libros nuevos.',
        ];
        if ($action && isset($placeholders[$action])) {
            echo "<div class='tools'><h3>📄 ".htmlspecialchars(ucwords(str_replace('_',' ',$action)))."</h3><p>".htmlspecialchars($placeholders[$action])."</p></div>";
        }
        ?>

    <?php endif; ?>

</body>
</html>
