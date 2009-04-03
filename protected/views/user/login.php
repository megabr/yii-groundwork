<h1>Login</h1>

<div class="yiiForm">
<?php echo CHtml::form(); ?>

<?php echo CHtml::errorSummary($user); ?>

<div class="simple">
<?php echo CHtml::activeLabel($user,'username'); ?>
<?php echo CHtml::activeTextField($user,'username') ?>
</div>

<div class="simple">
<?php echo CHtml::activeLabel($user,'password'); ?>
<?php echo CHtml::activePasswordField($user,'password') ?>
</div>

<div class="action">
<?php // echo CHtml::activeCheckBox($user,'rememberMe'); ?> Remember me next time<br/>
<?php echo CHtml::submitButton('Login'); ?>
</div>

</form>
</div><!-- yiiForm -->
<p>
<?php echo CHtml::link('Lost your username?', array('user/recover')); ?>
<br />
Don't have an account? <?php echo CHtml::link('Create one', array('user/create')); ?>.
</p>