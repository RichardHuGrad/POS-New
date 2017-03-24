<header>
    <?php echo $this->element('navbar'); ?>
</header>

<div class="container" ng-app="app" ng-controller="printPageCtrl">

    <div class="">
        <input type="text" name="" value="" ng-model="newType">
        <button class="btn btn-info" type="button" name="button" ng-click="insertType(newType)">Insert New Type</button>

    </div>
    <select class="" name="" ng-model="selectedType" ng-options="type for type in types">
    </select>
    <button class="btn btn-info" type="button" name="button" ng-click="insertLine(selectedType)">Insert Line</button>
    <!-- line details -->
    <table>
        <tr class="">
            <th>Content</th>
            <th>Offset X</th>
            <th>Index</th>
            <th>Lang</th>
            <th>Bold</th>
        </tr>
        <tr ng-repeat="(key, value) in data | filter: typeFilter(selectedType)">
            <td><input class="form-control" type="text" name="" ng-model="value.content"></td>
            <td><input class="form-control" type="text" name="" ng-model="value.offset_x"></td>
            <td><input class="form-control" type="text" name="" ng-model="value.line_index"></td>
            <td>
                <select class="form-control" name="" ng-model="value.lang_code" ng-options="x for x in ['en', 'zh']">
                </select>
            </td>
            <td>
                <select class="form-control" name="" ng-model="value.bold" ng-options="x for x in ['0', '1']">
            </td>
            <td>
                <button class="btn btn-info" type="button" name="button" ng-click="updateLine(value.id, value.type, value.content, value.offset_x, value.line_index, value.lang_code, value.bold)">Update</button>
                <button class="btn btn-danger" type="button" name="button" ng-click="deleteLine(value.id)">Delete</button>
            </td>
        </tr>
    </table>

</div>




<?php echo $this->Html->script(array('lib/angular.min.js', 'lib/lodash.min.js', 'lib/angular-filter.min.js','angular/app.js', 'angular/controllers/printPage.js' )); ?>
