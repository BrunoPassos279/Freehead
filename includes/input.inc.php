
<div class="input-grupo <?= isset($inputClass) ? $inputClass : '' ?>">

    <!-- Título do input -->
    <label for="<?= $inputId ?>"><?= $inputLabel ?></label>
    
     <!-- Campo de entrada de dados-->
    <input 
        type="<?= $inputTipo ?>" 
        id="<?= $inputId ?>" 
        name="<?= $inputId ?>"
        placeholder="<?= $inputPlaceholder ?>"
    >
</div>