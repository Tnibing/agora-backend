{{-- This file is used for menu items by any Backpack v6 theme --}}
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('dashboard') }}"><i class="las la-chart-pie"></i> Estadísticas</a></li>

<x-backpack::menu-separator title="Administración de usuarios" />
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('user') }}"><i class="la la-user"></i> Usuarios</a></li>
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('') }}"><i class="la la-user-slash"></i> Peticiones de eliminación</a></li>

<x-backpack::menu-separator title="Administración de noticias" />
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('article') }}"><i class="la la-newspaper"></i> Noticias</a></li>
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('tag') }}"><i class="la la-tags"></i> Categorías</a></li>

<x-backpack::menu-separator title="Moderación" />
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('comment') }}"><i class="la la-comments"></i> Comentarios</a></li>
