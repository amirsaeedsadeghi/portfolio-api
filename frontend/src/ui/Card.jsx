import { createContext } from "react";
import PropTypes from "prop-types";

const CardContext = createContext();

function Card({ children }) {
    return (
        <CardContext.Provider value={{}}>
            <article className="rounded-2xl overflow-hidden shadow-lg bg-secondary-light dark:bg-dark-blue flex flex-col justify-between">
                <div className="p-6 flex flex-col gap-6">{children}</div>
            </article>
        </CardContext.Provider>
    );
}

function Header({ children }) {
    return <header className="mt-4">{children}</header>;
}
function Title({ children }) {
    return <h5 className="text-2xl font-semibold mt-5">{children}</h5>;
}

function Body({ children }) {
    return <div>{children}</div>;
}
function Footer({ children }) {
    return <footer className="mt-5">{children}</footer>;
}

Card.Header = Header;
Card.Title = Title;
Card.Body = Body;
Card.Footer = Footer;

Card.propTypes = { children: PropTypes.node.isRequired };
Header.propTypes = { children: PropTypes.node.isRequired };
Title.propTypes = { children: PropTypes.node.isRequired };
Body.propTypes = { children: PropTypes.node.isRequired };
Footer.propTypes = { children: PropTypes.node.isRequired };

export default Card;
