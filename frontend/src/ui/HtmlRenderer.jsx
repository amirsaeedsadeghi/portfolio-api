import DOMPurify from "dompurify";
import clsx from "clsx";
import PropTypes from "prop-types";

HtmlRenderer.propTypes = {
    html: PropTypes.string.isRequired,
    className: PropTypes.string,
};

function HtmlRenderer({ html, className }) {
    const cleanHtml = DOMPurify.sanitize(html);
    return (
        <div
            className={clsx("richtext", className)}
            dangerouslySetInnerHTML={{ __html: cleanHtml }}
        />
    );
}

export default HtmlRenderer;
