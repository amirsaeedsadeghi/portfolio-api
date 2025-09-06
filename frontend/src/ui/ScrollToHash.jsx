import { useEffect } from "react";
import { useLocation } from "react-router-dom";

function ScrollToHash() {
  const location = useLocation();
  useEffect(
    function () {
      if (!location.hash) return;
      if (location.hash) {
        const id = location.hash.substring(1);
        const element = document.getElementById(id);
        if (element) element.scrollIntoView({ behavior: "smooth" });
      }
    },
    [location.hash]
  );
  return null;
}

export default ScrollToHash;
