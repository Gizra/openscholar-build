<?php
$sidebar_first_column = !empty($page['sidebar_first']) ? 4 : 0;
$sidebar_second_column = !empty($page['sidebar_second']) ? 4 : 0;
$content_column = 12 - ($sidebar_first_column + $sidebar_second_column);

?>
<header id="header">
  <div class="container">
    <div class="row">

      <div class="col-md-6 col-xs-12">
        <?php print render($page['header_first']); ?>
      </div>
      <div class="col-md-6">
        <div class="row">
          <div class="col-md-6 col-xs-12">
            <?php print render($page['header_second']); ?>
          </div>
          <div class="col-md-6 col-xs-12">
            <?php print render($page['header_third']); ?>
          </div>
        </div>
      </div>
    </div>
    <div class="header-border"></div>
    <div class="row">
      <div class="col-md-12">
        <?php print render($page['header_bottom']); ?>

        <?php if($menu_bar = render($page['menu_bar'])): ?>
          <!--main menu region beg-->
          <?php print $menu_bar; ?>
          <!--main menu region end-->
        <?php endif; ?>
      </div>
    </div>
  </div>
</header>


<section id="section">
  <div class="container">

    <?php if (!empty($messages)): ?>
      <div class="row">
        <div class="col-md-12"> <?php print render($messages); ?></div>
      </div>
    <?php endif; ?>

    <div class="row">
      <div class="col-md-12">
        <div id="border"></div>
        <?php if (!empty($tag)): ?><<?php print $tag; ?> id="main-content"><?php endif; ?>
        <?php if ($is_front || !empty($use_content_regions)): ?>
          <?php print render($title_prefix); ?>
          <?php if (!empty($skip_link)): ?>
            <a name="<?php echo $skip_link; ?>"></a>
          <?php endif; ?>

          <?php if (!$is_front && $title): ?>
            <header id="main-content-header">
              <h1 id="page-title"<?php print $attributes; ?>>
                <?php print $title; ?>
              </h1>
            </header>
          <?php endif; ?>

          <?php print render($title_suffix); ?>
          <div class="row">
          <!---Place the sidebar in two different places due to the page tpl logic. --!>
          <?php if ($sidebar_first = render($page['sidebar_first'])): ?>
            <!--sidebar first region beg-->
              <div class="col-md-<?php print $sidebar_first_column; ?> col-xs-12"><?php print $sidebar_first; ?></div>
            <!--sidebar first region end-->
          <?php endif; ?>

          <?php if ($page['content_top'] || $page['content_first'] || $page['content_second'] || $page['content_bottom']): ?>
            <!--front panel regions beg-->
              <div id="content-panels" class="col-md-<?php print $content_column; ?> cols-xs-12 at-panel gpanel panel-display content clearfix">
                <?php print render($page['content_top']); ?>
                <?php print render($page['content_first']); ?>
                <?php print render($page['content_second']); ?>
                <?php print render($page['content_bottom']); ?>
              </div>
            <!--front panel regions end-->
          <?php endif; ?>
        <?php endif; ?>

        <?php if (!$is_front && empty($use_content_regions)): ?>
          <?php if (!empty($skip_link)): ?>
            <a name="<?php echo $skip_link; ?>"></a>
          <?php endif; ?>
          <?php print render($title_prefix); ?>
          <?php if ($primary_local_tasks || $secondary_local_tasks || $action_links = render($action_links)): ?>
            <header id="main-content-header" class="clearfix">
              <?php if (!empty($primary_local_tasks) || !empty($secondary_local_tasks) || $action_links): ?>
                <div id="tasks">
                  <?php if ($primary_local_tasks): ?>
                    <ul class="tabs primary clearfix"><?php print render($primary_local_tasks); ?></ul>
                  <?php endif; ?>
                  <?php if ($secondary_local_tasks): ?>
                    <ul class="tabs secondary clearfix"><?php print render($secondary_local_tasks); ?></ul>
                  <?php endif; ?>
                  <?php if ($action_links = render($action_links)): ?>
                    <ul class="action-links clearfix"><?php print $action_links; ?></ul>
                  <?php endif; ?>
                </div>
              <?php endif; ?>
            </header>
          <?php endif; ?>
          <?php print render($title_suffix); ?>

          <?php if (!$is_front && $title): ?>
            <?php if ($title && $breadcrumb && !drupal_is_front_page()):?>
              <div class="row">
                <div class="col-md-12">
                  <div class="row-col-md-<?php print $sidebar_first_column; ?> col-md-<?php print $sidebar_first_column; ?>">
                    <?php print $breadcrumb; ?>
                  </div>
                </div>
              </div>
            <?php endif; ?>
          <?php endif; ?>

          <?php if ($sidebar_first = render($page['sidebar_first'])): ?>
            <!---Place the sidebar in two different places due to the page tpl logic. --!>
            <!--sidebar first region beg-->
            <div class="col-md-<?php print $sidebar_first_column; ?> col-xs-12">
              <?php print $sidebar_first; ?>
            </div>
            <!--sidebar first region end-->
          <?php endif; ?>

          <?php if ($content = render($page['content'])): ?>
            <div id="content" class="col-md-<?php print $content_column; ?> col-xs-12">
              <header id="main-content-header">
                <?php if (!empty($skip_link)): ?>
                  <a name="<?php echo $skip_link; ?>"></a>
                <?php endif; ?>
                <h1 id="page-title"<?php print $attributes; ?>>
                  <?php print $title; ?>
                </h1>
              </header>
              <?php if (!empty($tabs)): ?>
                <?php print render($tabs); ?>
              <?php endif; ?>

              <?php print $content; ?>
            </div>
          <?php endif; ?>

        <?php endif; ?>

        <?php if ($sidebar_second = render($page['sidebar_second'])): ?>
          <!--sidebar second region beg-->
          <div class="col-md-<?php print $sidebar_second_column; ?> col-xs-12"><?php print $sidebar_second; ?></div>
          <!--sidebar second region end-->
        <?php endif; ?>
      <?php if (!empty($tag)): ?></<?php print $tag; ?>><!--main content ends--><?php endif; ?>
      </div>
    </div>
  </div>
</section>
<footer id="footer">
  <div  class="container">
    <div class="row">
      <!-- Three column 3x33 Gpanel -->
      <?php if ($page['footer_top'] || $page['footer_first'] || $page['footer'] || $page['footer_third'] || $page['footer_bottom']): ?>

        <?php
          $footer_first = !empty($page['footer_first']) ? 4 : 0;
          $footer_third = !empty($page['footer_third']) ? 4 : 0;
          $footer = 12 - ($footer_first + $footer_third);
        ?>

        <div class="at-panel gpanel panel-display footer clearfix">
          <div class="col-md-<?php print $footer_first; ?> col-xs-12"><?php print render($page['footer_first']); ?></div>
          <div class="col-md-<?php print $footer; ?> col-xs-12"><?php print render($page['footer']); ?></div>
          <div class="col-md-<?php print $footer_third; ?> col-xs-12"><?php print render($page['footer_third']); ?></div>
          <div class="col-md-12 col-xs-12"><?php print render($page['footer_bottom']); ?></div>
        </div>

        <!--footer region end-->
      <?php endif; ?>
      <div id="powerby-login">
        <?php print $bottom_bar; ?>
        <span class="footer_logo">
          <?php print $footer_home_page_link; ?>
        </span>
        <span class="border-right"></span>
        <ul class="menu">
          <li class="col-md-4"><?php print $footer_menu['reserve']; ?></li>
          <li class="col-md-4"><?php print $footer_menu['update']; ?></li>
          <li class="col-md-4"><?php print $footer_menu['term']; ?></li>
        </ul>
        <div class="login">
          <?php
          if (isset($login_link)) {
            print render($login_link);
          }
          ?>
        </div>
      </div>
    </div>
  </div>
</footer>
