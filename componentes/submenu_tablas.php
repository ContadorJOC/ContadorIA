<!-- componentes/submenu_tablas.php -->
    <style>
        .submenu-tablas {
            position: fixed;
            top: 60px; /* debajo del menú superior */
            left: 0;
            width: 100%;
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 15px;
            padding: 12px 20px;
            background-color: #ecf0f1;
            z-index: 998;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }

        .submenu-tablas a {
            display: flex;
            align-items: center;
            gap: 8px;
            background: white;
            padding: 10px 16px;
            border-radius: 8px;
            text-decoration: none;
            color: #2c3e50;
            font-size: 14px;
            font-weight: 500;
            box-shadow: 0 2px 6px rgba(0,0,0,0.08);
            transition: 0.2s ease;
        }

        .submenu-tablas a:hover {
            background-color: #dfe6e9;
            transform: translateY(-2px);
        }

        .submenu-tablas i {
            font-style: normal;
            font-size: 16px;
        }
    </style>

    <div class="submenu-tablas">
        <a href="crear_tabla.php"><i>➕</i>Crear</a>
        <a href="ver_tablas.php"><i>📄</i>Ver</a>
        <a href="configurar_tablas.php"><i>🛠️</i>Configurar</a>
    </div>
