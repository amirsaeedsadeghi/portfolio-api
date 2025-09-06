import { FaGithub, FaLinkedin, FaEnvelope } from "react-icons/fa6";
import PropTypes from "prop-types";

Footer.propTypes = {
    aboutMe: PropTypes.string.isRequired,
};

function Footer({ aboutMe }) {
    const { githubUrl, linkedinUrl } = aboutMe;
    const currentDate = new Date();
    return (
        <footer className="bg-secondary-light dark:bg-secondary-dark text-light py-4">
            <div className="max-w-7xl mx-auto px-6 flex flex-col items-center gap-6">
                <div className="flex gap-6 text-2xl">
                    <a
                        href={githubUrl}
                        target="_blank"
                        className="hover:text-secondary transition"
                    >
                        <FaGithub />
                    </a>
                    <a
                        href={linkedinUrl}
                        target="_blank"
                        className="hover:text-secondary transition"
                    >
                        <FaLinkedin />
                    </a>
                    <a
                        href="mailto:amirsaeed.sadeghi@gmail.com"
                        className="hover:text-secondary transition"
                    >
                        <FaEnvelope />
                    </a>
                </div>

                <p className="text-sm text-center">
                    &copy; {currentDate.getFullYear()} Amirsaeed. All rights
                    reserved.
                </p>
            </div>
        </footer>
    );
}

export default Footer;
