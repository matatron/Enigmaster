<style>
	.myGrid {
		width: 100%;
		height: 400px;
	}
</style>
<h1><?=$room->name;?></h1>
<div ng-controller="StatisticsController" ng-init="roomId = <?=$roomId; ?>">
	<div ui-grid="gridOptions" class="myGrid" ></div>
</div>
