<script>
	import CSquare from "./CSquare.vue";
	export default {
		name: "CRow",
		components: {
			CSquare,
		},
		props: {
			row: Number,
			columns: Number,
			rowData: Array,
		},
		data() {
			return {};
		},
		methods: {
			selectSlot(index) {
				this.$emit("selectSlot", this.row, index);
			},
			hoverColumn(index) {
				
			},
			getSlotKey(index) {
				return "r" + this.row + "c" + index + "t" + this.rowData[index];
			},
		},
	};
</script>

<template>
	<div class="base-row">
		<CSquare
			v-for="item, index in columns"
			:key="getSlotKey(index)"
			:column="index"
			:ctype="rowData[index]"
			@click.native="selectSlot(index)"
			@mouseover="hoverColumn(index)"
		/>
	</div>
</template>

<style scoped>
.base-row {
	position: relative;
	display: block;
	width: max-content;
	height: 50px;
}
</style>