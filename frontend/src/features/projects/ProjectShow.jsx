import {
    FaGithub,
    FaArrowUpRightFromSquare,
    FaArrowLeft,
} from "react-icons/fa6";
import { Link } from "react-router-dom";
import ImageGalleryGrid from "../../ui/ImageGalleryGrid";
import PropTypes from "prop-types";
import HtmlRenderer from "../../ui/HtmlRenderer";
import { Suspense } from "react";
import SpinnerMini from "../../ui/SpinnerMini";

ProjectShow.propTypes = {
    project: PropTypes.array.isRequired,
};

function ProjectShow({ project }) {
    const {
        primaryImage,
        images,
        title,
        description,
        client,
        demoLink,
        github,
        category,
        role,
        startDate,
    } = project;
    return (
        <section className="px-6 py-12 bg-white dark:bg-black max-w-7xl mx-auto text-light">
            <h1 className="text-4xl font-bold mb-12 mt-6">{title}</h1>

            <div className="grid grid-cols-1 md:grid-cols-3 gap-10">
                {/* Left Column Project Overview */}
                <div className="md:col-span-2 flex flex-col gap-8">
                    <Suspense fallback={<SpinnerMini />}>
                        <ImageGalleryGrid
                            images={images ?? []}
                            primaryImage={primaryImage}
                        />
                    </Suspense>
                    <div className="leading-7 space-y-4">
                        <div className="font-bold text-xl mb-4">
                            Project Overview
                        </div>
                        <HtmlRenderer html={description} />
                    </div>
                </div>
                {/* Right Column Details */}
                <div className="md:col-span-1 flex flex-col justify-start gap-y-4">
                    <div className="font-bold text-xl mb-6">Details</div>
                    <div className="space-y-4 text-sm">
                        <p>
                            <strong>Date:</strong> {startDate}
                        </p>
                        <p>
                            <strong>Role:</strong> {role}
                        </p>
                        {client && (
                            <p>
                                <strong>Client:</strong> {client}
                            </p>
                        )}

                        <p>
                            <strong>Category:</strong> {category}
                        </p>
                    </div>
                    <div className="flex flex-col gap-4">
                        {github && (
                            <a
                                href={github}
                                target="_blank"
                                className="flex items-center justify-center gap-2 rounded-lg bg-primary hover:bg-secondary text-white py-2 transition"
                            >
                                <FaGithub className="text-xl" />
                                <span>Source Code</span>
                            </a>
                        )}
                        {demoLink && (
                            <a
                                href={demoLink}
                                target="_blank"
                                className="flex items-center justify-center gap-2 rounded-lg bg-primary hover:bg-secondary text-white py-2 transition"
                            >
                                <FaArrowUpRightFromSquare className="text-xl" />
                                <span>Live Demo</span>
                            </a>
                        )}

                        <Link
                            to="/#projects"
                            className="flex items-center justify-center gap-2 rounded-lg bg-primary hover:bg-secondary text-white py-2 transition"
                        >
                            <FaArrowLeft className="text-lg" />
                            <span>Back</span>
                        </Link>
                    </div>
                </div>
            </div>
        </section>
    );
}

export default ProjectShow;
