import PropTypes from "prop-types";

import TerminalCursor from "./TerminalCursor";

TerminalPrompt.propTypes = {
    command: PropTypes.string,
    active: PropTypes.bool,
};

function TerminalPrompt({command = "",active = false}) {
    return (
        <div className="flex flex-wrap">
            <span className="text-[#7fdbca]">
                amirsaeed@Amirs-MacBook-Pro
            </span>

            <span className="text-[#d6deeb]">:</span>

            <span className="text-[#82aaff]">
                ~/social
            </span>

            <span className="mr-2 text-[#d6deeb]">
                &nbsp;$
            </span>

            {command && (
                <span className="text-[#d6deeb]">
                    {command}
                </span>
            )}

            {active && <TerminalCursor />}
        </div>
    );
}

export default TerminalPrompt;