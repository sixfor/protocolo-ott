<?php
  $sidebar = sidebarUser($_SESSION["user_ott"][0]["id_rol"]);
?>
<nav class="navbar-default navbar-static-side" role="navigation">
  <div class="sidebar-collapse">
    <ul class="nav metismenu" id="side-menu">
      <li class="nav-header">
        <div class="dropdown profile-element"> <span>
          <img alt="image" class="img-circle" src="public/img/<?php echo $_SESSION["user_ott"][0]["icono_perfil"]; ?>" height="50" width="50" />
        </span>
        <a data-toggle="dropdown" class="dropdown-toggle" href="#">
          <span class="clear"> <span class="block m-t-xs"> <strong class="font-bold"><?php echo $_SESSION["user_ott"][0]["nombre"]; ?></strong>
          </span> <span class="text-muted text-xs block"><?php echo ucfirst($_SESSION["user_ott"][0]["nombre_rol"]); ?> <b class="caret"></b></span> </span> </a>
          <ul class="dropdown-menu animated fadeInRight m-t-xs">
            <!-- <li><a href="profile.html">Perfil</a></li> -->
            <!-- <li><a href="contacts.html">consulta</a></li>
            <li><a href="mailbox.html">Mailbox</a></li> -->
            <li class="divider"></li>
            <li><a href="?login=logout">Salir</a></li>
          </ul>
        </div>
        <div class="logo-element">
          IN+
        </div>
      </li>
      <?php
        echo $sidebar;
      ?>

    </ul>
  </div>
</nav>
