<script>
	import CRow from "./CRow.vue";

	export default {
		name: "Home",
		components: {
			CRow,
		},
		props: {
			rows: Number,
			columns: Number,
			finish: Boolean,
			mode: Number,
			difficulty: Number,
		},
		data() {
			return {
				turn: 1,
				gridData: [],
				rowClicks: [],
			};
		},
		created() {
			this.initializeGrid();
		},
		methods: {
			initializeGrid() {
				for (let r = 0; r < this.rows; r++) {
					this.gridData.push(Array(this.columns).fill(0));
				}
				this.rowClicks = Array(this.rows).fill(0);
			},
			changeTurn(val) {
				this.turn = val;
				this.$emit("update:turn", val);
			},
			resetGame() {
				this.changeTurn(1);
				this.gridData = [];
				this.initializeGrid();
				this.$forceUpdate();
			},
			canSelectSlot(row, column) {
				return this.gridData[row][column] == 0 && this.gridData[0][column] == 0;
			},
			invalidateRow(row) {
				this.rowClicks[row]++;
				this.$forceUpdate();
			},
			selectSlot(row, column) {
				if (this.finish) {
					return;
				}

				if (this.canSelectSlot(row, column)) {
					this.selectAvailableRow(column);

					// Request enemy's response
					if (this.mode == 1 && this.turn == 2) {
						// Make it look like the server is thinking :)
						setTimeout(this.requestMoveFromRemote, 2000);
					}
				}
			},
			getGridObject() {
				let obj = {};
				for (let row = 0; row < this.gridData.length; row++) {
					obj[row] = this.gridData[row];
				}
				return obj;
			},
			requestMoveFromRemote() {
				let data = {
					grid: JSON.stringify(this.getGridObject()),
					difficulty: this.difficulty,
				};

				this.$fetch(
					"/api/play/move",
					data,
					(result) => {
						if (result.status) {
							this.selectAvailableRow(result.column);
						} else {
							console.log("Could not fetch Enemy response. Please try again later.");
						}
					},
					(error) => {
						console.log("Could not fetch Enemy response. Please try again later.");
						console.log(error);
					}
				);
			},
			selectAvailableRow(column) {
				for (let r = this.rows - 1; r >= 0; r--) {
					if (this.gridData[r][column] == 0) {
						this.doSelection(r, column);
						break;
					}
				}
			},
			doSelection(row, column) {
				this.gridData[row][column] = this.turn;
				this.invalidateRow(row);
				if (!this.performWinCheck(row, column)) {
					this.changeTurn(this.turn == 1 ? 2 : 1);
				} else {
					// Current turn wins
					this.winAction();
				}
			},
			winAction() {
				this.$emit("update:finish", true);
			},
			performWinCheck(row, column) {
				return (
					this.performWinCheckRow(row) ||
					this.performWinCheckColumn(column) ||
					this.performWinCheckDiagonal(row, column)
				);
			},
			performWinCheckDiagonal(row, column) {
				// Index 0: Top Left | 1: Top Right | 2: Bottom Left | 3: Bottom Right
				let streaks = Array(4).fill(1);
				// Direction x,y for each check (4 possible directions)
				let direction = [
					[-1, -1],
					[-1, 1],
					[1, -1],
					[1, 1],
				];

				for (let ci = 0; ci < direction.length; ci++) {
					for (let x = 1; x <= 3; x++) {
						let nextRow = this.gridData[row + x * direction[ci][0]];
						if (nextRow !== undefined) {
							let nextType = nextRow[column + x * direction[ci][1]];
							streaks[ci] = nextType == this.turn ? streaks[ci] + 1 : 0;
						}
					}
				}

				return streaks.includes(4);
			},
			performWinCheckRow(row) {
				let streaks = 0;

				for (let c = 0; c < this.columns; c++) {
					let type = this.gridData[row][c];
					streaks = type == this.turn ? streaks + 1 : 0;
					if (streaks == 4) {
						return true;
					}
				}

				return false;
			},
			performWinCheckColumn(column) {
				let streaks = 0;

				for (let r = 0; r < this.rows; r++) {
					let type = this.gridData[r][column];
					streaks = type == this.turn ? streaks + 1 : 0;
					if (streaks == 4) {
						return true;
					}
				}

				return false;
			},
		},
	};
</script>

<template>
	<div class="base-grid">
		<CRow
			v-for="item, index in rows"
			:key="'r' + index + 'u' + rowClicks[index]"
			:row="index"
			:columns="columns"
			:rowData="gridData[index]"
			@selectSlot="selectSlot"
		/>
	</div>
</template>

<style scoped>
.base-grid {
	width: max-content;
	height: max-content;
}
</style>