// Retrieve canvas and context

const canvas = document.getElementById("battleCanvas");
const ctx = canvas.getContext("2d");

// Start music on first click
document.addEventListener(
	"click",
	function () {
		document.getElementById("audio").play();
		document.getElementById("audiotext").textContent = "";
	},
	{ once: true }
);

// Get battle log data
let battleLog = battleLogData;
console.log(battleLog);

// Define variables for animation
let turnIndex = 0;
let turn = 0;
let currentHealth1, currentHealth2, targetHealth1, targetHealth2;

// Load Pokémon images
const pokemonImages = {};

// Extract initialStats from battleLog
const initialStats = battleLog.find(
	(entry) => entry.initialStats
)?.initialStats;

// Assign unique IDs to each Pokémon
if (initialStats) {
	initialStats.pokemon1.id = 1;
	initialStats.pokemon2.id = 2;

	// Load Pokémon images using unique IDs
	pokemonImages[`pokemon${initialStats.pokemon1.id}`] = new Image();
	pokemonImages[`pokemon${initialStats.pokemon2.id}`] = new Image();

	// Update setPokemonImage to use unique IDs
	function setPokemonImage(pokemonNom, pokeId, defaultId) {
		fetch(`https://pokeapi.co/api/v2/pokemon/${pokemonNom.toLowerCase()}`)
			.then((response) => {
				if (response.ok) {
					return response.json();
				}
				throw new Error("Pokemon not found");
			})
			.then((data) => {
				let sprite =
					pokeId === 1 ? data.sprites.back_default : data.sprites.front_default;
				if (sprite !== null) {
					pokemonImages[`pokemon${pokeId}`].src = sprite;
				} else {
					pokemonImages[`pokemon${pokeId}`].src =
						pokeId === 1
							? `https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/back/${defaultId}.png`
							: `https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/${defaultId}.png`;
				}
			})
			.catch(() => {
				pokemonImages[`pokemon${pokeId}`].src =
					pokeId === 1
						? `https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/back/${defaultId}.png`
						: `https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/${defaultId}.png`;
			});
	}

	// Set images for both Pokémon
	setPokemonImage(initialStats.pokemon1.nom, initialStats.pokemon1.id, 35);
	setPokemonImage(initialStats.pokemon2.nom, initialStats.pokemon2.id, 35);

	// Initialize health values
	currentHealth1 = targetHealth1 = initialStats.pokemon1.pvMax;
	currentHealth2 = targetHealth2 = initialStats.pokemon2.pvMax;

	// Set up Pokémon positions with unique IDs
	pokemonPositions = {
		[`pokemon${initialStats.pokemon1.id}`]: {
			x: 130,
			y: 220,
			originalX: 130,
			originalY: 220,
		}, // Bottom left, front-facing
		[`pokemon${initialStats.pokemon2.id}`]: {
			x: 500,
			y: 80,
			originalX: 500,
			originalY: 80,
		}, // Top right, back-facing
	};
}

// Add movement offset
let movementOffset = 50;

// Variables to handle faint animation and opacities
let isAnimatingFaint = false;
const pokemonOpacities = {
	[`pokemon${initialStats.pokemon1.id}`]: 1,
	[`pokemon${initialStats.pokemon2.id}`]: 1,
};

function drawHealthBar(x, y, current, max, width = 200) {
	const height = 20;
	const percentage = Math.max(0, current) / max; // Ensure percentage is not negative
	const color =
		percentage > 0.5 ? "green" : percentage > 0.2 ? "orange" : "red";

	// Create health bar container
	ctx.fillStyle = "#333";
	ctx.fillRect(x, y, width, height);

	// Add health-bar class properties
	ctx.fillStyle = color;
	ctx.fillRect(x, y, width * percentage, height);
}

function animateHealth() {
	const speed = 0.01; // Even slower animation
	let needsAnimation = false;

	// Smooth transition for health values and prevent them from going below 0
	if (Math.abs(currentHealth1 - targetHealth1) > 0.1) {
		needsAnimation = true;
		currentHealth1 += (targetHealth1 - currentHealth1) * speed;
		currentHealth1 = Math.max(0, currentHealth1); // Clamp to 0
	} else {
		currentHealth1 = targetHealth1;
	}

	if (Math.abs(currentHealth2 - targetHealth2) > 0.1) {
		needsAnimation = true;
		currentHealth2 += (targetHealth2 - currentHealth2) * speed;
		currentHealth2 = Math.max(0, currentHealth2); // Clamp to 0
	} else {
		currentHealth2 = targetHealth2;
	}

	return needsAnimation;
}

let animationFrameId = null;

function draw() {
	console.log("Drawing frame");
	ctx.clearRect(0, 0, canvas.width, canvas.height);
	// Get opacities for each Pokémon
	const opacity1 = pokemonOpacities[`pokemon${initialStats.pokemon1.id}`];
	const opacity2 = pokemonOpacities[`pokemon${initialStats.pokemon2.id}`];

	// Only draw first Pokémon if not fainted
	if (opacity1 > 0) {
		ctx.globalAlpha = opacity1;
		ctx.drawImage(
			pokemonImages[`pokemon${initialStats.pokemon1.id}`],
			pokemonPositions[`pokemon${initialStats.pokemon1.id}`].x,
			pokemonPositions[`pokemon${initialStats.pokemon1.id}`].y,
			200,
			200
		);

		// Draw health bar and text with same opacity
		ctx.strokeStyle = "black";
		ctx.lineWidth = 2;
		ctx.strokeRect(
			pokemonPositions[`pokemon${initialStats.pokemon1.id}`].x,
			pokemonPositions[`pokemon${initialStats.pokemon1.id}`].y - 30,
			200,
			20
		);
		drawHealthBar(
			pokemonPositions[`pokemon${initialStats.pokemon1.id}`].x,
			pokemonPositions[`pokemon${initialStats.pokemon1.id}`].y - 30,
			currentHealth1,
			initialStats.pokemon1.pvMax
		);

		// Draw name and HP
		ctx.font = "20px Arial";
		ctx.lineWidth = 3;
		ctx.strokeStyle = "white";
		ctx.fillStyle = "black";
		ctx.textBaseline = "top";
		const text1 = `${initialStats.pokemon1.nom} HP: ${Math.round(
			currentHealth1
		)}/${initialStats.pokemon1.pvMax}`;
		ctx.strokeText(
			text1,
			pokemonPositions[`pokemon${initialStats.pokemon1.id}`].x,
			pokemonPositions[`pokemon${initialStats.pokemon1.id}`].y - 50
		);
		ctx.fillText(
			text1,
			pokemonPositions[`pokemon${initialStats.pokemon1.id}`].x,
			pokemonPositions[`pokemon${initialStats.pokemon1.id}`].y - 50
		);
	}

	// Only draw second Pokémon if not fainted
	if (opacity2 > 0) {
		ctx.globalAlpha = opacity2;
		ctx.drawImage(
			pokemonImages[`pokemon${initialStats.pokemon2.id}`],
			pokemonPositions[`pokemon${initialStats.pokemon2.id}`].x,
			pokemonPositions[`pokemon${initialStats.pokemon2.id}`].y,
			200,
			200
		);

		// Draw health bar and text with same opacity
		ctx.strokeStyle = "black";
		ctx.lineWidth = 2;
		ctx.strokeRect(
			pokemonPositions[`pokemon${initialStats.pokemon2.id}`].x,
			pokemonPositions[`pokemon${initialStats.pokemon2.id}`].y - 30,
			200,
			20
		);
		drawHealthBar(
			pokemonPositions[`pokemon${initialStats.pokemon2.id}`].x,
			pokemonPositions[`pokemon${initialStats.pokemon2.id}`].y - 30,
			currentHealth2,
			initialStats.pokemon2.pvMax
		);

		// Draw name and HP
		ctx.font = "20px Arial";
		ctx.lineWidth = 3;
		ctx.strokeStyle = "white";
		ctx.fillStyle = "black";
		ctx.textBaseline = "top";
		const text2 = `${initialStats.pokemon2.nom} HP: ${Math.round(
			currentHealth2
		)}/${initialStats.pokemon2.pvMax}`;
		ctx.strokeText(
			text2,
			pokemonPositions[`pokemon${initialStats.pokemon2.id}`].x,
			pokemonPositions[`pokemon${initialStats.pokemon2.id}`].y - 50
		);
		ctx.fillText(
			text2,
			pokemonPositions[`pokemon${initialStats.pokemon2.id}`].x,
			pokemonPositions[`pokemon${initialStats.pokemon2.id}`].y - 50
		);
	}

	ctx.globalAlpha = 1; // Reset global alpha

	// Continue animating if necessary
	if (animateHealth() || isAnimatingFaint) {
		animationFrameId = requestAnimationFrame(draw); // Modify this line
	} else {
		animationFrameId = null; // Add this line
	}
}

function updateTurnNumber(newTurn) {
	// Only update if there's a valid turn number
	if (!newTurn) return;

	const turnNumberElement = document.getElementById("turnNumber");
	if (turnNumberElement) {
		turnNumberElement.textContent = `Turn: ${newTurn}`;
	} else {
		const newTurnNumberElement = document.createElement("div");
		newTurnNumberElement.id = "turnNumber";
		newTurnNumberElement.className =
			"text-lg font-bold mb-1 text-center text-gray-300";
		document.getElementById("battleLogOverlay").prepend(newTurnNumberElement);
	}
}

const logContainer = document.getElementById("battleLogContainer");
const battleLogOverlay = document.getElementById("battleLogOverlay");

function initOverlay() {
	if (battleLogOverlay) {
		battleLogOverlay.innerHTML = "";

		// Move turn number back to the top of battleLogOverlay
		const turnNumberElement = document.createElement("div");
		turnNumberElement.id = "turnNumber";
		turnNumberElement.className =
			"text-lg font-bold mb-1 text-center text-gray-300";
		battleLogOverlay.prepend(turnNumberElement);
	}

	// Add a new battle log container below the fight
	const battleLogContainer = document.createElement("div");
	battleLogContainer.id = "battleLogContainer";
	battleLogContainer.className =
		"mt-6 p-4 bg-gray-800 rounded-lg shadow-md max-w-2xl mx-auto text-left text-gray-100";
}
// Wait for DOM element to load
document.addEventListener("DOMContentLoaded", () => {
	initOverlay();
});

// Update the updateBattleLog function
async function updateBattleLog(turnData) {
	if (!turnData) {
		console.error("Turn data is undefined.");
		return;
	}

	const { tour, action, statut, fin } = turnData;

	updateTurnNumber(tour);
	updatePokemonStatus(statut.statut);
	updateActionText(action, fin);
	// Append to the new battle log container
	const battleLogContainer = document.getElementById("battleLogContainer");
	if (battleLogContainer) {
		const logEntry = document.createElement("div");
		logEntry.className =
			"log-entry mb-4 p-2 bg-gray-700 rounded shadow text-gray-100";
		logEntry.innerHTML = `
			<strong class="text-lg text-gray-300">Turn ${turnData.tour}:</strong> 
			<span class="text-white">${action?.action.attaquant}</span> used 
			<span class="text-gray-400">${action?.action.attackUsed}</span>.
			${
				action?.action.touche
					? `<span class="text-gray-200">Deals ${Math.round(
							action.action.degats
					  )} damage!${
							action.action.efficacite > 1
								? " It's super effective!"
								: action.action.efficacite < 1
								? " It's not very effective..."
								: ""
					  }</span>`
					: `<span class="text-red-500">The attack missed!</span>`
			}
			${
				action?.action.capaciteSpeciale
					? `<div class="mt-2 text-yellow-400">${action.action.attaquant} used its special ability!</div>`
					: ""
			}
			${
				fin
					? `<div class="mt-2 text-green-400">${
							fin.fin.vainqueur
					  } wins with ${Math.round(fin.fin.pvRestants)} HP remaining!</div>`
					: ""
			}
		`;
		battleLogContainer.appendChild(logEntry);
	}
}

// Add a new function to update Pokémon status
function updatePokemonStatus(statut) {
	if (statut) {
		targetHealth1 = Math.max(0, statut.pokemon1.pv);
		targetHealth2 = Math.max(0, statut.pokemon2.pv);

		// Update the health bars
		drawHealthBar(100, 250, currentHealth1, initialStats.pokemon1.pvMax);
		drawHealthBar(500, 50, currentHealth2, initialStats.pokemon2.pvMax);

		// Redraw the canvas to reflect health changes
		draw();

		// Check if any Pokémon has fainted
		if (
			targetHealth1 <= 0 &&
			pokemonOpacities[`pokemon${initialStats.pokemon1.id}`] > 0
		) {
			animateFaint(`pokemon${initialStats.pokemon1.id}`);
		}

		if (
			targetHealth2 <= 0 &&
			pokemonOpacities[`pokemon${initialStats.pokemon2.id}`] > 0
		) {
			animateFaint(`pokemon${initialStats.pokemon2.id}`);
		}
	}
}

let faintedPokemonId = null;

function updateActionText(action, fin) {
	if (action) {
		const act = action.action;
		// Use the id to determine attacker
		const attackerId = act.id; // Retrieve id from action
		animateAttack(`pokemon${attackerId}`).then(() => {
			// Use id to animate
			const actionText = `${act.attaquant} uses ${act.attackUsed}!`;
			// Update action text in the UI
			let actionTextElement = document.getElementById("actionText");
			if (!actionTextElement) {
				actionTextElement = document.createElement("div");
				actionTextElement.id = "actionText";
				actionTextElement.className = "text-md text-left text-gray-300 mb-2";
				battleLogOverlay.appendChild(actionTextElement);
			}
			actionTextElement.textContent = actionText;

			if (act.touche) {
				// If there was a miss text from previous turn, remove it
				const missTextElement = document.getElementById("missText");
				if (missTextElement) {
					missTextElement.textContent = "";
					missTextElement.remove();
				}

				let effectivenessText = "";
				if (act.efficacite > 1) {
					effectivenessText = " It's super effective!";
				} else if (act.efficacite < 1) {
					effectivenessText = " It's not very effective...";
				}

				const damageText = `Deals ${Math.round(
					act.degats
				)} damage!${effectivenessText}`;
				// Update damage text in the UI
				let damageTextElement = document.getElementById("damageText");
				if (!damageTextElement) {
					damageTextElement = document.createElement("div");
					damageTextElement.id = "damageText";
					damageTextElement.className = "text-md text-left text-gray-200 mb-2";
					battleLogOverlay.appendChild(damageTextElement);
				}
				damageTextElement.textContent = damageText;
			} else {
				// Hide damage text if attack missed
				const damageTextElement = document.getElementById("damageText");
				if (damageTextElement) {
					damageTextElement.textContent = "";
					damageTextElement.remove();
				}

				const missText = "The attack missed!";
				// Update miss text in the UI
				let missTextElement = document.getElementById("missText");
				if (!missTextElement) {
					missTextElement = document.createElement("div");
					missTextElement.id = "missText";
					missTextElement.className = "text-md text-left text-red-500 mb-2";
					battleLogOverlay.appendChild(missTextElement);
				}
				missTextElement.textContent = missText;
			}

			if (act.capaciteSpeciale) {
				// Update special attack text in the UI
				let specialAttackTextElement =
					document.getElementById("specialAttackText");
				if (!specialAttackTextElement) {
					specialAttackTextElement = document.createElement("div");
					specialAttackTextElement.id = "specialAttackText";
					specialAttackTextElement.className =
						"text-md text-left text-yellow-400 mb-2";
					battleLogOverlay.appendChild(specialAttackTextElement);
				}
				specialAttackTextElement.textContent = `${act.attaquant} used its special ability!`;
			}

			if (fin) {
				const end = fin.fin;
				setTimeout(() => {
					const winText = `${end.vainqueur} wins with ${Math.round(
						end.pvRestants
					)} HP remaining!`;
					// Update win text in the UI
					let winTextElement = document.getElementById("winText");
					if (!winTextElement) {
						winTextElement = document.createElement("div");
						winTextElement.id = "winText";
						winTextElement.className =
							"text-lg text-left text-green-400 font-semibold mt-4";
						battleLogOverlay.appendChild(winTextElement);
					}
					winTextElement.textContent = winText;

					// Determine the fainted Pokémon ID
					faintedPokemonId =
						targetHealth1 <= 0
							? `pokemon${initialStats.pokemon1.id}`
							: `pokemon${initialStats.pokemon2.id}`;

					// Create the 'Heal and Restart' button
					createHealButton(faintedPokemonId);
				}, 1000);
			}
		});
	}

	if (fin) {
		const end = fin.fin;

		const winText = `${end.vainqueur} wins with ${Math.round(
			end.pvRestants
		)} HP remaining!`;
		// Update win text in the UI
		let winTextElement = document.getElementById("winText");
		if (!winTextElement) {
			winTextElement = document.createElement("div");
			winTextElement.id = "winText";
			winTextElement.className =
				"text-lg text-left text-green-400 font-semibold mt-1";
			battleLogOverlay.appendChild(winTextElement);
		}
		winTextElement.textContent = winText;
	}
}

// Function to animate attack movement
function animateAttack(pokemonId) {
	return new Promise((resolve) => {
		const direction =
			pokemonPositions[pokemonId].x === pokemonPositions[pokemonId].originalX
				? pokemonId.endsWith("1")
					? movementOffset
					: -movementOffset
				: 0;
		let step = 0;
		const steps = 10;
		const interval = setInterval(() => {
			if (step < steps) {
				pokemonPositions[pokemonId].x += direction / steps;
				step++;
				draw();
			} else {
				clearInterval(interval);
				// Return to original position
				let returnStep = 0;
				const returnInterval = setInterval(() => {
					if (returnStep < steps) {
						pokemonPositions[pokemonId].x -= direction / steps;
						returnStep++;
						draw();
					} else {
						clearInterval(returnInterval);
						resolve();
					}
				}, 20);
			}
		}, 20);
	});
}

// Function to animate fainting Pokémon
function animateFaint(pokemonId) {
	isAnimatingFaint = true;
	let step = 0;
	const steps = 50;

	const interval = setInterval(() => {
		if (step < steps) {
			pokemonPositions[pokemonId].y += 2; // Move downwards
			pokemonOpacities[pokemonId] -= 1 / steps; // Decrease opacity
			step++;
			draw();
		} else {
			clearInterval(interval);
			pokemonOpacities[pokemonId] = 0;
			isAnimatingFaint = false;
		}
	}, 20);
}

// Update the animateTurn function
let isAnimating = false;

function animateTurn() {
	if (turnIndex >= battleLog.length || isAnimating) return;
	isAnimating = true;
	const currentLog = battleLog[turnIndex];
	if (currentLog.turn) {
		updateBattleLog(currentLog.turn).then(() => {
			isAnimating = false;
			turnIndex++;
			setTimeout(animateTurn, 2000);
		});
	} else {
		isAnimating = false;
		turnIndex++;
		setTimeout(animateTurn, 2000);
	}
}

// Start animation after images load
Promise.all(
	Object.values(pokemonImages).map(
		(img) => new Promise((resolve) => (img.onload = resolve))
	)
).then(() => {
	draw();
	animateTurn();
});

// Function to create the 'Heal and Restart' button
function createHealButton(pokemonId) {
	const button = document.createElement("button");
	button.textContent = "Heal and Restart";
	button.className =
		"bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded";

	// Get canvas element
	const battleCanvas = document.getElementById("battleCanvas");

	// Position button relative to canvas
	button.style.position = "absolute";
	button.style.top = `${
		battleCanvas.offsetTop + battleCanvas.height / 2 - 20
	}px`; // Center vertically

	if (pokemonId === `pokemon${initialStats.pokemon1.id}`) {
		button.style.left = `${battleCanvas.offsetLeft + 100}px`;
	} else {
		button.style.left = `${
			battleCanvas.offsetLeft + battleCanvas.width - 200
		}px`;
	}

	// Append button to canvas container
	battleCanvas.parentElement.appendChild(button);

	// Add click event listener to the button
	button.addEventListener("click", () => {
		// Remove the button
		button.remove();
		// Call the server-side function to heal and restart
		restartFight();
	});
}

// Function to restart the fight
function restartFight() {
	// Cancel any ongoing animation frame
	cancelAnimationFrame(draw); // Modify this line
	if (animationFrameId) {
		cancelAnimationFrame(animationFrameId);
		animationFrameId = null;
	}

	// Call the server-side function to heal and restart
	fetch("/battle/restart", {
		method: "POST",
		headers: {
			"Content-Type": "application/json",
		},
		body: JSON.stringify({
			faintedPokemon:
				faintedPokemonId === `pokemon${initialStats.pokemon1.id}` ? 1 : 2,
		}),
	})
		.then((response) => response.json())
		.then((data) => {
			// Reset battle variables
			battleLog = data;
			turnIndex = 0;
			isAnimating = false;

			// Reset Pokémon opacities
			pokemonOpacities[`pokemon${initialStats.pokemon1.id}`] = 1;
			pokemonOpacities[`pokemon${initialStats.pokemon2.id}`] = 1;

			// Reset positions
			pokemonPositions[`pokemon${initialStats.pokemon1.id}`].x =
				pokemonPositions[`pokemon${initialStats.pokemon1.id}`].originalX;
			pokemonPositions[`pokemon${initialStats.pokemon1.id}`].y =
				pokemonPositions[`pokemon${initialStats.pokemon1.id}`].originalY;
			pokemonPositions[`pokemon${initialStats.pokemon2.id}`].x =
				pokemonPositions[`pokemon${initialStats.pokemon2.id}`].originalX;
			pokemonPositions[`pokemon${initialStats.pokemon2.id}`].y =
				pokemonPositions[`pokemon${initialStats.pokemon2.id}`].originalY;

			// Animate health restoration for the fainted Pokémon
			if (faintedPokemonId === `pokemon${initialStats.pokemon1.id}`) {
				currentHealth1 = 0;
				targetHealth1 = initialStats.pokemon1.pvMax;
			} else {
				currentHealth2 = 0;
				targetHealth2 = initialStats.pokemon2.pvMax;
			}

			initOverlay();
			// Clear action texts
			const turnNumberElement = document.getElementById("turnNumber");
			if (turnNumberElement) {
				const turnNumber = turnNumberElement.textContent;
				battleLogOverlay.innerHTML = "";
				turnNumberElement.textContent = turnNumber;
				battleLogOverlay.appendChild(turnNumberElement);
			}

			// Redraw and restart animation
			draw();
			animateTurn();
		})
		.catch((error) => {
			console.error("Error restarting fight:", error);
		});
}
