<header>
    <?php echo $this->element('navbar'); ?>
</header>

<div class="container" ng-app="app" ng-controller="printPageCtrl">
    <form class="" action="index.html" method="post">

    </form>

    <!-- type selector -->
    <select class="form-control" name="type" value="Type">
        <option value="receipt-header">receipt Header</option>
        <option value="kitchen-header">kitchen Header</option>
        <option value="receipt-footer">receipt footer</option>
    </select>

    <!-- <select class="" name="" ng-model="" ng-options="type for type in types">

    </select> -->


    <button class="btn btn-info" type="button" name="button" ng-click="insertLine('receipt-header')">Insert Line</button>


    <!-- line details -->
    <div class="" ng-repeat="(key, value) in data">
        <div class="col-sm-8">
            <label for="">Content</label>
            <input class="form-control" type="text" name="" ng-model="value.content">
        </div>
        <div class="col-sm-1">
            <label for="">Offset X</label>
            <input class="form-control" type="text" name="" ng-model="value.offset_x">
        </div>
        <div class="col-sm-1">
            <label for="">Index</label>
            <input class="form-control" type="text" name="" ng-model="value.line_index">
        </div>
        <div class="col-sm-2">
            <button class="btn btn-info" type="button" name="button" ng-click="updateLine('receipt-header', value.content, value.offset_x, value.line_index)">Update</button>
            <button class="btn btn-danger" type="button" name="button" ng-click="deleteLine('receipt-header', value.line_index)">Delete</button>
        </div>

    </div>

</div>




<?php echo $this->Html->script(array('lib/angular.min.js', 'angular/app.js', 'angular/controllers/printPage.js', 'lib/lodash.min.js')); ?>
