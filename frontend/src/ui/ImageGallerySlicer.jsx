import PropTypes from "prop-types";
import ImageItem from "./ImageItem";

ImageGallerySlicer.propTypes = {
    images: PropTypes.array,
    primaryImage: PropTypes.string.isRequired,
    alt: PropTypes.string,
};

function ImageGallerySlicer({ images, primaryImage, alt }) {
    return (
        <div className="gallery-row">
            {images?.map((image, index) => (
                <ImageItem
                    src={image.image}
                    alt={alt ?? `image gallery ${index + 1}`}
                    key={image.id}
                />
            ))}
            <ImageItem src={primaryImage} alt={alt ?? "main image"} />
        </div>
    );
}

export default ImageGallerySlicer;
