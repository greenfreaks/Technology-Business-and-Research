<form role="search" method="get" action="<?php home_url("/");?>" class="container">
    <div class="input-field">
        <input placeholder="Buscar..." value="<?php echo get_search_query();?>" id="search" type="search" name="s" required>
        <label class="label-icon" for="search"><i class="material-icons">search</i></label>
        <i class="material-icons">close</i>
    </div>
</form>
