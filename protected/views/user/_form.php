<div class="yiiForm">

<p>
Fields with <span class="required">*</span> are required.
</p>

<?php echo CHtml::form(); ?>

<?php echo CHtml::errorSummary($user); ?>

<div class="simple">
<?php echo CHtml::activeLabelEx($user,'username'); ?>
<?php echo CHtml::activeTextField($user,'username',array('size'=>60,'maxlength'=>128)); ?>
</div>
<div class="simple">
<?php echo CHtml::activeLabelEx($user,'password'); ?>
<?php echo CHtml::activePasswordField($user,'password',array('size'=>60,'maxlength'=>128)); ?>
</div>
<div class="simple">
<?php echo CHtml::activeLabelEx($user,'email'); ?>
<?php echo CHtml::activeTextField($user,'email',array('size'=>60,'maxlength'=>128)); ?>
</div>
<div class="simple">
<?php echo CHtml::activeLabelEx($user,'createTime'); ?>
<?php echo CHtml::activeTextField($user,'createTime'); ?>
</div>
<div class="simple">
<?php echo CHtml::activeLabelEx($user,'updateTime'); ?>
<?php echo CHtml::activeTextField($user,'updateTime'); ?>
</div>

<div class="action">
<?php echo CHtml::submitButton($update ? 'Save' : 'Create'); ?>
</div>

</form>
</div><!-- yiiForm -->