/**
 * External dependencies
 */
const { useBlockProps } = wp.blockEditor;

/**
 * Internal dependencies
 */
import Block from './block';
import './editor.scss';

export const Edit = ( props: unknown ): JSX.Element => {
	const blockProps = useBlockProps();
	return (
		<div { ...blockProps }>
			<Block { ...props } />
		</div>
	);
};
