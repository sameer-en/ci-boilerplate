<!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">

      <!-- Sidebar user panel (optional) -->
      <div class="user-panel">
        <div class="pull-left image">
          <img src="<?php echo site_url();?>assets/dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <p><?php echo $this->session->userdata('cib_userName');?></p>
          <!-- Status -->
          <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
      </div>

      <!-- search form (Optional) -->
      <!-- <form action="#" method="get" class="sidebar-form">
        <div class="input-group">
          <input type="text" name="q" class="form-control" placeholder="Search...">
          <span class="input-group-btn">
              <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
              </button>
            </span>
        </div>
      </form> -->
      <!-- /.search form -->

      <!-- Sidebar Menu -->
      <ul class="sidebar-menu" data-widget="tree">
        <li class="header">Menu</li>
        <!-- Optionally, you can add icons to the links -->
        <li <?php if($menuActive == 'dashboard'){echo 'class="active"';}?> ><a href="#"><i class="fa fa-link"></i> <span>Dashboard</span></a></li>
        <!-- <li><a href="#"><i class="fa fa-link"></i> <span>Another Link</span></a>
        </li> -->
        <li class="treeview  <?php if($menuActive == 'docx' || $menuActive == 'xls'){echo 'active';}?>">
          <a href="#"><i class="fa fa-link"></i> <span>Translate</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
          </a>
          <ul class="treeview-menu">
            <li <?php if($menuActive == 'docx'){echo 'class="active"';}?>><a href="<?php echo site_url('translate/word');?>">Word(.docx) Files</a></li>
            <li <?php if($menuActive == 'xls'){echo 'class="active"';}?>><a href="<?php echo site_url('translate/excel');?>">Excel(.xls) Files</a></li>
          </ul>
        </li>
        <li class="treeview">
          <a href="#"><i class="fa fa-link"></i> <span>Dictionary</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="<?php echo site_url('dictionary/');?>">Dictionaries</a></li>
            <li><a href="<?php echo site_url('dictionary/languages');?>">Languages</a></li>
            
          </ul>
        </li>
        <li class="treeview">
          <a href="#"><i class="fa fa-link"></i> <span>Statistics</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="<?php echo site_url('stat/by-user');?>">By User</a></li>
            <li><a href="<?php echo site_url('stat/by-file');?>">By Files</a></li>
          </ul>
        </li>
        <li><a href="#"><i class="fa fa-link"></i> <span>Help</span></a>
        </li>
      </ul>
      <!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
  </aside>