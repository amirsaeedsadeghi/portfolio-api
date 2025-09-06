import { useEffect } from "react";
import { FaSun, FaMoon } from "react-icons/fa6";
import { useLocalStorageState } from "../hooks/useLocalStorageState";

function ToggleTheme() {
  const [isDarkMode, setIsDarkMode] = useLocalStorageState(
    window.matchMedia &&
      window.matchMedia("(prefers-color-scheme: dark)").matches,
    "theme"
  );

  function toggleTheme() {
    setIsDarkMode((mode) => !mode);
  }

  useEffect(
    function () {
      if (isDarkMode) {
        document.documentElement.classList.add("dark");
        document.documentElement.classList.remove("light");
      } else {
        document.documentElement.classList.add("light");
        document.documentElement.classList.remove("dark");
      }
    },
    [isDarkMode]
  );

  return (
    <div
      onClick={toggleTheme}
      className="relative flex items-center justify-between w-16 h-[30px] rounded-full bg-gray-300 px-1.5 cursor-pointer"
    >
      <FaSun className="text-sm transition-colors" />
      <div className="absolute bg-gray-600 top-[5px] w-5 h-5 rounded-full transition-all duration-300 dark:right-1"></div>
      <FaMoon className="text-sm transition-colors" />
    </div>
  );
}

export default ToggleTheme;
