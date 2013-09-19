<?php

namespace Invigle;

/**
 * The conversation node will be connected to the participants via the conversation edges.
 * The actual message node will have an edge from the conversation node to the message node daisy-chained in a chronological order.
 * Each time a new message is added to a conversation the message order path will be updated by:
 * 1) deleting all incoming and outgoing edges from/to the updated conversation node and other conversation nodes only
 * 2) deleting the old 'latest edge'
 * 3) creating the new 'latest edge' to the conversation node which is the parent of the updated message
 * 4) creating an edge with priority 'latest edge - 1' between the old 'latest conversation node' and the current 'latest conversation node'
 * 5) merging the previous and next conversation nodes along the path where the current 'latest conversation node' has created a 'gap'
 * @access public
 * @author Grant
 */
class Messaging {
	
    /**
	 * A list of participant UIDs connected to the conversation node CVID
	 */
	public $_participants;
	
    /**
	 * This will be an array of 3 fields timestamp, message and userID (originator of the message)
	 */
	public $_messagesArray;
    
   	public function createLatestEdge($conversId) {
   	    // not yet implemented
   	    }
   	public function deleteConversEdges($conversId) {
   	   // not yet implemented
   	    }
}
?>