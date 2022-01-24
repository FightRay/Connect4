<script>
	import CGrid from "./components/CGrid.vue";

	export default {
		name: "Game",
		components: {
			CGrid,
		},
		data() {
			return {
				difficulties: {
					0: {
						name: "Easy",
					},
					1: {
						name: "Medium",
					},
					2: {
						name: "Hard",
					},
				},
				page: 0,
				mode: 0, // 0: Local BlueVsRed | 1: Player vs Remote AI
				difficulty: 0,
				finish: false,
				turn: 1,
				textFinalSwitch: false,
				mouseHoverFirst: true,
			};
		},
		methods: {
			goToMenu() {
				this.page = 0;
				this.resetGame();
			},
			resetGame() {
				this.finish = false;
				this.turn = 1;
			},
			toggleFinishButton(hover) {
				if (this.mouseHoverFirst) {
					this.mouseHoverFirst = false;
					return;
				}
				this.textFinalSwitch = hover;
			},
			getInstructionText() {
				switch (this.page) {
					case 0:
						return "Select Game Mode";
					case 1:
						return "Select Difficulty";
					case 2:
						return "In your turn, select a slot on the grid by clicking on one of the squares.";
				}
			},
			startGame(diff) {
				this.difficulty = parseInt(diff);
				this.$fetch(
					"/play/start",
					{
						difficulty: this.difficulty
					},
					(result) => {
						if (result.status) {
							this.page++;
						} else {
							alert("Could not start game, please try again later.");
						}
					},
					(error) => {
						alert("Could not start game, please try again later.");
					}
				);
			}
		},
	};
</script>

<template>
	<div class="wrapper">
		<span class="title-text text-center-screen text-neon-green">Connect4 Game</span>
		<span class="subtitle-text text-center-screen text-neon-green">Created by Ray</span>
		<span class="footer-text text-center-screen text-neon-green">Made with ♥ for the Aon assignment</span>
		<template v-if="page < 2">
			<div class="start-screen">
				<span class="instruction-text text-neon-blue">{{ getInstructionText() }}</span>
				<div class="grid-style">
					<template v-if="page == 0">
						<div class="button" @click="mode = 1; page++">
							<span>Player Vs Server</span>
						</div>
						<div class="button" @click="mode = 0; page++">
							<span>Player Vs Player<br>(Local)</span>
						</div>
					</template>
					<template v-else>
						<div
							v-for="item, index in difficulties"
							:key="index"
							class="button button-difficulty"
							@click="startGame(index);"
						>
							<span>{{ difficulties[index].name }}</span>
						</div>
						<div class="button" @click="page--">
							<span>Back</span>
						</div>
					</template>
				</div>
			</div>
		</template>
		<template v-else-if="page == 2">
			<div class="game-position">
				<template v-if="!finish">
					<span
						v-if="mode == 0"
						class="text-game-center text-neon-green"
					>{{ this.turn == 1 ? "Blue Turn" : "Red Turn" }}</span>
					<span
						v-if="mode == 1"
						class="text-game-center text-neon-green"
					>{{ this.turn == 1 ? "Your Turn" : "Waiting for enemy's move..." }}</span>
				</template>
				<CGrid
					:columns="7"
					:rows="6"
					:mode="mode"
					:difficulty="difficulty"
					:turn.sync="turn"
					:finish.sync="finish"
					class="grid-style"
					:class="{ 'game-disabled': finish || (mode == 1 && turn == 2) }"
					ref="c-grid"
				/>
				<template v-if="finish">
					<span
						v-if="!textFinalSwitch"
						class="text-final"
						@mouseenter="toggleFinishButton(true);"
						:class="this.turn == 1 ? 'text-neon-green' : 'text-neon-red'"
					>{{ this.turn == 1 ? "YOU WIN" : "YOU LOSE" }}</span>
					<span
						v-else
						class="text-final text-neon-blue"
						@mouseleave="toggleFinishButton(false);"
						@click="goToMenu();"
					>Menu</span>
				</template>
			</div>
		</template>
	</div>
</template>

<style scoped>
.game-disabled {
	pointer-events: none;
}

.text-final:hover {
	filter: brightness(1.25);
}

.text-final {
	position: fixed;
	font-size: 64px;
	margin: auto;
	inset: 0;
	width: 400px;
	height: 90px;
	background-color: #000000e0;
	border-radius: 15px;
	display: flex;
	justify-content: center;
	align-items: center;
	cursor: pointer;
}

.text-game-center {
	display: block;
	text-align: center;
	margin-bottom: 20px;
	font-size: 18px;
}

.status-text {
	top: 300px;
}

.instruction-text {
	display: block;
	font-size: 24px;
	margin-bottom: 50px;
	text-align: center;
}

.footer-text {
	bottom: 25px;
	font-size: 14px;
}

.subtitle-text {
	top: 100px;
}

.title-text {
	top: 50px;
	font-size: 32px;
}

.text-center-screen {
	position: absolute;
	margin: 0 auto;
	left: 0;
	right: 0;
	width: max-content;
}

.text-neon-blue {
	color: #fff;
	text-shadow: 0 0 7px #fff, 0 0 10px #fff, 0 0 21px #fff, 0 0 42px #00b7ff,
		0 0 82px #00b7ff, 0 0 92px #00b7ff, 0 0 102px #00b7ff, 0 0 151px #00b7ff;
}

.text-neon-green {
	color: #fff;
	text-shadow: 0 0 7px #fff, 0 0 10px #fff, 0 0 21px #fff, 0 0 42px #0fa,
		0 0 82px #0fa, 0 0 92px #0fa, 0 0 102px #0fa, 0 0 151px #0fa;
}

.text-neon-red {
	color: #fff;
	text-shadow: 0 0 7px #fff, 0 0 10px #fff, 0 0 21px #fff, 0 0 42px #ff0040,
		0 0 82px #ff0040, 0 0 92px #ff0040, 0 0 102px #ff0040, 0 0 151px #ff0040;
}

.button-difficulty:nth-child(3) span {
	color: #ff1800;
}

.button-difficulty:nth-child(2) span {
	color: #d3e200;
}

.button-difficulty:nth-child(1) span {
	color: #00e21c;
}

.button span {
	position: absolute;
	margin: auto;
	inset: 0;
	display: block;
	width: max-content;
	height: max-content;
	font-size: 18px;
	color: #fff;
	font-weight: 600;
	text-align: center;
}

.button:nth-last-child(1) {
	border-bottom: 0;
	height: 100px;
}

.button:hover {
	filter: brightness(1.25);
	background-color: #242424a0;
}

.button {
	width: 100%;
	height: 98px;
	background-color: #000000a0;
	cursor: pointer;
	border-bottom: 2px solid #000;
	position: relative;
}

.start-screen {
	position: absolute;
	margin: auto;
	inset: 0;
	width: 400px;
	height: max-content;
}

.grid-style {
	border: 5px solid #000;
}

.game-position {
	position: absolute;
	margin: auto;
	inset: 0;
	width: max-content;
	height: max-content;
}

.wrapper {
	width: 100%;
	height: 100%;
}
</style>