import absFloor from"./abs-floor";export default function toInt(o){var r=+o,t=0;return 0!==r&&isFinite(r)&&(t=absFloor(r)),t}