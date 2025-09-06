import { Suspense } from "react";
import { Link } from "react-router-dom";
import PropTypes from "prop-types";
import { FaArrowRight } from "react-icons/fa6";
import TechStacks from "../../../ui/TechStacks";
import Card from "../../../ui/Card";
import SpinnerMini from "../../../ui/SpinnerMini";
import ImageGallerySlicer from "../../../ui/ImageGallerySlicer";

ProjectCard.propTypes = {
    project: PropTypes.shape({
        id: PropTypes.oneOfType([PropTypes.string, PropTypes.number])
            .isRequired,
        title: PropTypes.string.isRequired,
        summary: PropTypes.string,
        stacks: PropTypes.arrayOf(PropTypes.string),
        images: PropTypes.arrayOf(PropTypes.string),
        primaryImage: PropTypes.string,
        demoLink: PropTypes.string,
        slug: PropTypes.string.isRequired,
    }).isRequired,
};

function ProjectCard({ project }) {
    const { images, primaryImage, title, summary, stacks, demoLink, slug } =
        project;
    return (
        <Card>
            <Card.Header>
                <Suspense fallback={<SpinnerMini />}>
                    <ImageGallerySlicer
                        images={images}
                        primaryImage={primaryImage}
                    />
                </Suspense>
            </Card.Header>
            <Card.Title>{title}</Card.Title>
            <Card.Body>
                {summary}
                <TechStacks stacks={stacks} />
            </Card.Body>
            <Card.Footer>
                <div className="flex flex-row gap-x-2 justify-between items-center my-4">
                    {demoLink && (
                        <a
                            href={demoLink}
                            className="text-secondary underline"
                            target="_blank"
                            rel="noopener noreferrer"
                        >
                            Live Demo
                        </a>
                    )}

                    <Link
                        to={`/projects/${slug}`}
                        className="inline-flex items-center gap-2 text-secondary hover:underline text-sm font-medium transition"
                    >
                        View Details
                        <FaArrowRight />
                    </Link>
                </div>
            </Card.Footer>
        </Card>
    );
}

export default ProjectCard;
