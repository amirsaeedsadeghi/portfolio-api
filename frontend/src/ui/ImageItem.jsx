import PropTypes from "prop-types";
import { useState } from "react";

ImageItem.propTypes = {
    src: PropTypes.string.isRequired,
    alt: PropTypes.string.isRequired,
    className: PropTypes.string,
    onClick: PropTypes.func,
};

function ImageItem({ src, alt, className, onClick }) {
    const [hasError, setHasError] = useState(false);
    const fallback = "/assets/img/fallback.webp";
    const baseSrc = hasError ? fallback : src;
    return (
        <img
            src={baseSrc}
            alt={alt}
            loading="lazy"
            decoding="async"
            sizes="(max-width:768px) 90vw, 60vw"
            className={className ?? "image-item"}
            onClick={onClick}
            onError={() => setHasError(true)}
        />
    );
}

export default ImageItem;
