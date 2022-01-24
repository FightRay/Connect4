<script>
	export default {
		name: "CSquare",
		components: {},
		props: {
			column: Number,
			ctype: Number // 0: Empty | 1: Player | 2: Enemy
		},
		data() {
			return {};
		},
		methods: {
			getCircleStyle() {
				switch (this.ctype) {
					case 1:
						return "background-color: #4fa1ff;"
					case 2:
						return "background-color: #ff4f73;"
					default:
						return "background-color: transparent;"
				}
			}
		}
	};
</script>

<template>
	<div class="base-square" :style="getCircleStyle()"></div>
</template>

<style scoped>

.base-square:hover {
	filter:opacity(0.75);
	filter:brightness(1.25);
	box-shadow: 0px 0px 5px 5px #000000a0;
}

.base-square {
	position: relative;
    display: inline-block;
    width: 35px;
    height: 35px;
    background-color: transparent;
    border: 7.5px solid #000;
    cursor: pointer;
    background-clip: border-box;
    border-radius: 35px;
	transition: all 0.25s;
}
</style>