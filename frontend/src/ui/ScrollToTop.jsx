import { useEffect } from "react";
import { useLocation, useNavigationType } from "react-router-dom";
import PropTypes from "prop-types";

ScrollToTop.propTypes = {
    target: PropTypes.shape({
        current: PropTypes.instanceOf(Element),
    }),
};

function ScrollToTop({ target }) {
    const { pathname } = useLocation();
    const navType = useNavigationType();

    useEffect(() => {
        if (navType === "PUSH" || navType === "REPLACE") {
            const el = target?.current ?? window;
            if ("scrollTo" in el)
                el.scrollTo({ top: 0, left: 0, behavior: "auto" });
            else if (el && el.scrollTop !== undefined) el.scrollTop = 0;
        }
    }, [pathname, navType, target]);

    return null;
}

ScrollToTop.defaultProps = {
    target: null,
};

export default ScrollToTop;
