<?php

namespace Invigle;

/**
 * The conversation node will be connected to the participants via the conversation edges.
 * The actual message node will have an edge from the conversation node to the message node daisy-chained in a chronological order.
 * @access public
 * @author Grant
 */
class Messaging
{

	/**
	 * A list of participant UIDs connected to the conversation node CVID
	 */
	public $_participants;

	/**
	 * This will be an array of 4 fields timestamp, message, userID (originator of the message) and messageID
	 */
	public $_messageArray;
    
   	/**
	 * This will be the chain/list of messages in chronological order starting with the latest
	 */
	public $_messages;
    
    public $_flag;
    
	/* The Class Constructor*/
	public function __construct()
	{
		$this->_participants = null;
		$this->_messageArray = null;
        $this->_messages = null;
	}

	public function getParticipants()
	{
		return $this->_participants;
	}

	public function getMessage()
	{
		return $this->_messageArray;
	}

	public function createConversation($convArray)
	{
		//Create a new conversation in neo4j
		$graph = new Graph();
		$queryString = "";
		foreach ($convArray as $key => $value)
		{
			$queryString .= "$key : \"$value\", ";
		}
		$queryString = substr($queryString, 0, -2);
		$conversation['query'] = "CREATE (n:Conversation {" . $queryString .
			"}) RETURN n;";
		$apiCall = $graph->neo4japi('cypher', 'JSONPOST', $conversation);

		//return the New Conversation ID.
		$bit = explode("/", $apiCall['data'][0][0]['self']);
		$convId = end($bit);
		return $convId;
	}

	/**
	 * This method takes as inputs a user ID and the ID of a conversation and adds a PARTICIPATES_IN edge to neo4j.
	 * @access public
	 * @param uID, convID
	 * @return the list of participants
	 */
	public function addUserConversation($uID, $convID)
	{
		$graph = new Graph();
		$connectionType = 'PARTICIPATES_IN';
		$graph->addConnection($uID, $convID, $connectionType);
		array_push($this->participants, uID);
		return $this->_participants;
	}

	public function createMessage($messageArray)
	{
		//Create the new message in neo4j
		$graph = new Graph();
		$queryString = "";
		foreach ($messageArray as $key => $value)
		{
			$queryString .= "$key : \"$value\", ";
		}
		$queryString = substr($queryString, 0, -2);
		$message['query'] = "CREATE (n:Message {" . $queryString . "}) RETURN n;";
		$apiCall = $graph->neo4japi('cypher', 'JSONPOST', $message);

		//return the New Message ID.
		$bit = explode("/", $apiCall['data'][0][0]['self']);
		$messageId = end($bit);
		return $messageId;
	}

	public function deleteMessage($messageID)
	{
		$graph = new Graph();
		$succ = $graph->deleteNodeByID($messageID);
		return $succ;
	}

	public function addMessageToConversation($messageID, $convID)
	{
		foreach ($this->_messageArray as $value)
        {
            if ($this->_messageArray[3] > $messageID) // When a message ID is higher than other message ID means that the former was a successor of the second
            {
                   $flag = 1;
                   break; 
            }
        
        }
        if ($flag = 1){
            break;
            }
        else 
        {
            $graph = new Graph();
            $connectionType = 'PART_OF';
            $graph->deleteConnection($this->_messages[0][3], $convID, $connectionType);
            $succ = $graph->addConnection($messageID, $convID, $connectionType);
            $messageID2 = $messageToMessage($messageID, $this->_messages[0][3]);      
        }
        
	}

	public function messageToMessage($messageID, $messageID2)
	{
		$graph = new Graph();
		$connectionType = 'NEXT';
		$succ = $graph->addConnection($messageID, $messageID2, $connectionType);
		return $succ;
	}

	public function deleteConversationUsersEdges($uID, $convID)
	{
		$graph = new Graph();
        $connectionType = 'PARTICIPATES_IN';
		foreach ($this->_participants as $value)
        {
            $graph->deleteConnection($value, $convID, $connectionType);
        }
	}
    
	public function createLatestEdge($convID)
	{
		// not yet implemented
	}
	public function deleteConversEdges($convID)
	{
		// not yet implemented
	}
}

?>