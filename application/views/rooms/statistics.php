<style>
    .myGrid {
        width: 100%;
        height: 400px;
    }
</style>
<h1><?=$room->name;?></h1>
<div ng-controller="StatisticsController" ng-init="roomId = <?=$roomId; ?>">
    <div ui-grid="gridOptions" class="myGrid" ></div>
    <table class="table responsive table-responsive table-stripped">
        <tr>
            <th>Total de grupos</th>
            <td>{{stats.count}}</td>
        </tr>
        <tr>
            <th>Porcentaje de éxito</th>
            <td>{{(stats.wins/stats.count*100).toFixed(1)}}%</td>
        </tr>
        <tr>
            <th>Tiempo mínimo</th>
            <td>{{stats.min*1000 | clock}}</td>
        </tr>
        <tr>
            <th>Tiempo máximo</th>
            <td>{{stats.max*1000 | clock}}</td>
        </tr>
        <tr>
            <th>Tiempo promedio</th>
            <td>{{stats.timeMean*1000 | clock}}</td>
        </tr>
        <tr>
            <th>Pistas promedio</th>
            <td>{{stats.clueMean.toFixed(1)}}</td>
        </tr>
        <tr>
            <th>Personas promedio</th>
            <td>{{stats.peopleMean.toFixed(1)}}</td>
        </tr>
    </table>
</div>