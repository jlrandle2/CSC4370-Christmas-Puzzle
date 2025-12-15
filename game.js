let gridSize = 4;
let tiles = [];
let emptyIndex = 0;

let timer = null;
let secondsElapsed = 0;
let timerRunning = false;

let helpsUsed = 0;
let moves = 0;

const puzzleBoard = document.getElementById("puzzle-board");
const timerDisplay = document.getElementById("timer");
const helpsUsedDisplay = document.getElementById("helps-used");
const sizeButtons = document.querySelectorAll(".size-btn");
const bgm = document.getElementById("bgm");
const moveSfx = document.getElementById("move-sfx");
let bgmStarted = false;

const winModal = document.getElementById("win-modal");
const winTime = document.getElementById("win-time");
const winHelpTime = document.getElementById("win-help-time");
const winHelps = document.getElementById("win-helps");
const winMoves = document.getElementById("win-moves");

function ensureBgmStarted() {
  if (bgmStarted || !bgm) return;

  bgm
    .play()
    .then(() => {
      bgmStarted = true;
      bgm.volume = 0.35;
    })
    .catch(() => {});
}

function playMoveSfx() {
  if (!moveSfx) return;
  moveSfx.currentTime = 0;
  moveSfx.play().catch(() => {});
}
function initGame() {
  const tileCount = gridSize * gridSize;

  tiles = [];
  for (let i = 1; i < tileCount; i++) tiles.push(i);
  tiles.push(null);

  shuffleTiles();
  emptyIndex = tiles.indexOf(null);

  resetTimer();
  helpsUsed = 0;
  moves = 0;
  updateHelpsUsed();

  hideWinModal();
  renderBoard();
}

function shuffleTiles() {
  for (let i = tiles.length - 1; i > 0; i--) {
    const j = Math.floor(Math.random() * (i + 1));
    [tiles[i], tiles[j]] = [tiles[j], tiles[i]];
  }
}

function renderBoard() {
  puzzleBoard.innerHTML = "";

  // Dynamic sizing
  const maxBoardSize = 420;
  const tileSize = Math.floor((maxBoardSize - (gridSize + 1) * 10) / gridSize);

  puzzleBoard.style.gridTemplateColumns = `repeat(${gridSize}, ${tileSize}px)`;
  puzzleBoard.style.width = `${gridSize * tileSize + (gridSize + 1) * 10}px`;

  tiles.forEach((value, index) => {
    const tile = document.createElement("div");

    tile.style.width = `${tileSize}px`;
    tile.style.height = `${tileSize}px`;

    if (value === null) {
      tile.className = "tile empty-tile";
    } else {
      tile.className = "tile";
      tile.textContent = value;
      tile.addEventListener("click", () => moveTile(index));
    }

    puzzleBoard.appendChild(tile);
  });
}

function isAdjacent(i1, i2) {
  const r1 = Math.floor(i1 / gridSize);
  const c1 = i1 % gridSize;
  const r2 = Math.floor(i2 / gridSize);
  const c2 = i2 % gridSize;

  return (
    (Math.abs(r1 - r2) === 1 && c1 === c2) ||
    (Math.abs(c1 - c2) === 1 && r1 === r2)
  );
}
function moveTile(index) {
  if (!isAdjacent(index, emptyIndex)) return;

  ensureBgmStarted();

  [tiles[index], tiles[emptyIndex]] = [tiles[emptyIndex], tiles[index]];
  emptyIndex = index;

  moves++;
  playMoveSfx();

  renderBoard();
  checkWin();
}

function shuffleBoard() {
  shuffleTiles();
  emptyIndex = tiles.indexOf(null);

  resetTimer();
  helpsUsed = 0;
  moves = 0;
  updateHelpsUsed();

  hideWinModal();
  renderBoard();
}

function helpMove() {
  ensureBgmStarted();

  for (let i = 0; i < tiles.length; i++) {
    const value = tiles[i];
    if (value === null) continue;

    const correctIndex = value - 1;

    if (i !== correctIndex) {
      const targetValue = tiles[correctIndex];

      tiles[correctIndex] = value;
      tiles[i] = targetValue;

      if (targetValue === null) {
        emptyIndex = i;
      }

      helpsUsed++;
      moves++;
      secondsElapsed += 30;

      updateHelpsUsed();
      updateTimerDisplay();
      playMoveSfx();
      renderBoard();
      checkWin();

      return;
    }
  }
}

function startTimer() {
  ensureBgmStarted();
  if (timerRunning) return;

  timerRunning = true;
  timer = setInterval(() => {
    secondsElapsed++;
    updateTimerDisplay();
  }, 1000);
}

function stopTimer() {
  clearInterval(timer);
  timerRunning = false;
}

function resetTimer() {
  stopTimer();
  secondsElapsed = 0;
  updateTimerDisplay();
}

function updateTimerDisplay() {
  const minutes = Math.floor(secondsElapsed / 60);
  const seconds = secondsElapsed % 60;
  timerDisplay.textContent = `${minutes}:${seconds
    .toString()
    .padStart(2, "0")}`;
}

function updateHelpsUsed() {
  helpsUsedDisplay.textContent = helpsUsed;
}

function saveGameStats() {
  fetch("save_game.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({
      time_taken: secondsElapsed,
      helps_used: helpsUsed,
      moves: moves,
    }),
  }).catch(() => {});
}
function showWinModal() {
  if (!winModal) return;

  winTime.textContent = timerDisplay.textContent;
  winHelpTime.textContent = helpsUsed * 30;
  winHelps.textContent = helpsUsed;
  winMoves.textContent = moves;

  winModal.classList.remove("hidden");
}

function hideWinModal() {
  if (!winModal) return;
  winModal.classList.add("hidden");
}

function closeWinModal() {
  hideWinModal();
}
function checkWin() {
  for (let i = 0; i < tiles.length - 1; i++) {
    if (tiles[i] !== i + 1) return;
  }

  stopTimer();
  saveGameStats();
  showWinModal();
}

function setupSizeSelector() {
  if (!sizeButtons || sizeButtons.length === 0) return;

  sizeButtons.forEach((btn) => {
    btn.addEventListener("click", () => {
      if (btn.classList.contains("active")) return;

      gridSize = parseInt(btn.dataset.size, 10);

      sizeButtons.forEach((b) => b.classList.remove("active"));
      btn.classList.add("active");

      initGame();
    });
  });
}

window.shuffleBoard = shuffleBoard;
window.helpMove = helpMove;
window.startTimer = startTimer;
window.closeWinModal = closeWinModal;

document.addEventListener("DOMContentLoaded", () => {
  setupSizeSelector();
  initGame();
});

function createSnowflakes() {
  const snowflakeCount = 12;
  for (let i = 0; i < snowflakeCount; i++) {
      setTimeout(() => {
          const snowflake = document.createElement('div');
          snowflake.className = 'snowflake';
          snowflake.textContent = '❄';
          snowflake.style.left = Math.random() * 100 + '%';
          snowflake.style.animationDuration = (Math.random() * 5 + 6) + 's';
          snowflake.style.fontSize = (Math.random() * 8 + 16) + 'px';
          document.body.appendChild(snowflake);
          setTimeout(() => snowflake.remove(), parseFloat(snowflake.style.animationDuration) * 1000);
      }, i * 300);
  }
}


createSnowflakes();
setInterval(createSnowflakes, 8000);


function applyTimeBasedTheme() {
  const hour = new Date().getHours();
  
  if (hour >= 6 && hour < 12) {
      
      document.body.style.filter = 'brightness(1.1)';
  } else if (hour >= 18 || hour < 6) {
      
      document.body.style.filter = 'brightness(0.85)';
  }
}


function checkPerformanceTheme() {
  const avgTime = secondsElapsed;
  
 
  if (avgTime < 120) {
      document.body.classList.add('sparkle-theme');
  }
}


applyTimeBasedTheme();
setInterval(applyTimeBasedTheme, 60000); 

let powerups = {
  freeze: 0,  
  star: 0,    
  bell: 0     
};


function checkPowerupEarned() {
  
  powerups.freeze = 1;
  powerups.star = 1;
  powerups.bell = 1;
  updatePowerupDisplay();
}

function updatePowerupDisplay() {
  document.getElementById('powerup-display').innerHTML = `
      ❄️ Freeze: ${powerups.freeze} | 
      ⭐ Star: ${powerups.star} | 
      🔔 Bell: ${powerups.bell}
  `;
}

function usePowerup(type) {
  if (powerups[type] <= 0) {
      showMessage("No " + type + " power-ups available!");
      return;
  }
  
  powerups[type]--;
  
  if (type === 'freeze') {
      
      stopTimer();
      showMessage("⏸️ Time frozen for 10 seconds!");
      setTimeout(() => {
          startTimer();
      }, 10000);
  } else if (type === 'star') {
      
      showMessage("⭐ Correct positions highlighted!");
      highlightCorrectTiles();
  } else if (type === 'bell') {
      
      helpMove();
      secondsElapsed -= 30; 
  }
  
  updatePowerupDisplay();
}

